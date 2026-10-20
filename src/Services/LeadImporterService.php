<?php

namespace NextDeveloper\CRM\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NextDeveloper\CRM\Database\Models\Accounts as CrmAccounts;
use NextDeveloper\CRM\Database\Models\ImporterRuns;
use NextDeveloper\CRM\Database\Models\Importers;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\Flow\Database\Models\Pipelines;
use NextDeveloper\Flow\Database\Models\Stages;
use NextDeveloper\Flow\Services\ItemsService;
use NextDeveloper\IAM\Database\Models\AccountUsers;
use NextDeveloper\IAM\Database\Models\Accounts as IamAccounts;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\IAM\Services\AccountsService as IamAccountsService;
use NextDeveloper\IAM\Services\UsersService;

/**
 * Generic, source-agnostic lead importer.
 *
 * A source adapter (Facebook, Google forms, CSV...) normalizes its lead into
 *   ['name', 'email', 'phone', 'fields' => [...], 'source' => 'facebook', 'form' => '<optional form key>']
 * and calls LeadImporterService::import($importer, $lead). The service then:
 *   1. de-duplicates the lead (payload hash + identity, never a source specific id),
 *   2. finds or creates the IAM user + account, fills the phone number,
 *   3. ensures a CRM account, creates the Opportunity,
 *   4. creates a Flow item in the pipeline/stage configured on the importer.
 * Every attempt is recorded in crm_importer_runs.
 *
 * Importer config (crm_importers.config) keys:
 *   flow_pipeline_id, flow_stage_id (uuids; stage optional -> first stage),
 *   opportunity_type (default 'sales'), dedupe_window_hours (default 24),
 *   default_country_code (e.g. '90', used to normalize local phone numbers),
 *   lead.external_id / lead.description (optional per-lead: unique id for one-opportunity-per-event sources, and the opportunity description),
 *   tags (list of strings put on every imported opportunity, and on the CRM account when the import creates it),
 *   forms.<form key>.{flow_pipeline_id, flow_stage_id, opportunity_type, tags} (per-form overrides; form tags are added to the importer's).
 */
class LeadImporterService
{
    /**
     * Cache key prefix (+ iam user id) set while the importer creates a customer's first account,
     * read by the lead-nurture opportunity job to avoid a duplicate opportunity for imported leads.
     */
    public const SKIP_NURTURE_CACHE_PREFIX = 'crm:lead-import:skip-nurture:';

    public const STATUS_PENDING   = 'pending';
    public const STATUS_IMPORTED  = 'imported';
    public const STATUS_DUPLICATE = 'duplicate';
    public const STATUS_FAILED    = 'failed';

    /**
     * Imports one normalized lead. Never throws; the outcome is stored on the returned run.
     * Runs as the system admin (same as the campaign opportunity job and the webhook services), because a queue
     * worker has no user and the customer's own user may not be able to see the configured pipeline. Every created
     * object is still assigned explicitly to the importer's account and user, so it belongs to the customer.
     */
    public static function import(Importers $importer, array $lead): ImporterRuns
    {
        return UserHelper::runAsAdmin(fn () => self::importAsOwner($importer, $lead));
    }

    private static function importAsOwner(Importers $importer, array $lead): ImporterRuns
    {
        $lead = self::normalize($importer, $lead);
        $hash = self::hash($importer, $lead);

        // Idempotency: a lead that is pending/imported for this importer is never processed twice
        $existing = ImporterRuns::withoutGlobalScopes()
            ->where('crm_importer_id', $importer->id)
            ->where('payload_hash', $hash)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_IMPORTED])
            ->first();

        if ($existing) {
            return $existing;
        }

        $run = ImporterRunsService::create([
            'crm_importer_id' => $importer->uuid,
            'status'          => self::STATUS_PENDING,
            'payload'         => $lead,
            'payload_hash'    => $hash,
            'iam_account_id'  => IamAccounts::withoutGlobalScopes()->find($importer->iam_account_id)->uuid,
            'iam_user_id'     => Users::withoutGlobalScopes()->find($importer->iam_user_id)->uuid,
        ]);

        try {
            $mapping = self::mapping($importer, $lead);

            if (!$lead['email'] && !$lead['phone']) {
                throw new \InvalidArgumentException('Lead has neither email nor phone number.');
            }

            if (!$mapping['pipeline']) {
                throw new \InvalidArgumentException('Importer has no valid flow pipeline configured.');
            }

            // Everything below is one unit of work; a failure must not leave half a customer behind
            $opportunity = DB::transaction(function () use ($importer, $lead, $mapping) {
                $user = self::findOrCreateUser($lead);
                [$iamAccount, $iamAccountCreated] = self::findOrCreateAccount($user);
                $crmAccount = self::findOrCreateCrmAccount($iamAccount, $lead);

                // Importer-controlled tags go on the CRM account only for a customer this import created from scratch
                // (decided by the creation itself, not by a separate lookup). An existing customer may belong to another
                // tenant and must not receive this importer's tags.
                if ($iamAccountCreated) {
                    self::applyTags($crmAccount, $mapping['tags'], fn ($uuid, $tags) => AccountsService::update($uuid, ['tags' => $tags]));
                }

                $duplicate = self::findRecentOpportunity($importer, $crmAccount, $mapping);

                if ($duplicate) {
                    // A repeat submission is not a new opportunity, but its tags still apply to the existing one
                    self::applyTags($duplicate, $mapping['tags'], fn ($uuid, $tags) => OpportunitiesService::update($uuid, ['tags' => $tags]));

                    return [$duplicate, true];
                }

                $opportunity = self::createOpportunity($importer, $lead, $crmAccount, $mapping);
                self::createFlowItem($opportunity, $iamAccount, $importer, $mapping);

                return [$opportunity, false];
            });

            [$opportunity, $isDuplicate] = $opportunity;

            $run = ImporterRunsService::update($run->uuid, [
                'status'      => $isDuplicate ? self::STATUS_DUPLICATE : self::STATUS_IMPORTED,
                'object_type' => Opportunities::class,
                'object_id'   => $opportunity->id,
                'imported_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error(__METHOD__ . '| Lead import failed | importer: ' . $importer->id . ' | ' . $e->getMessage());

            // Failed runs are excluded from the unique hash index, so the lead can be retried later
            $run = ImporterRunsService::update($run->uuid, [
                'status' => self::STATUS_FAILED,
                'error'  => $e->getMessage(),
            ]);
        }

        return $run;
    }

    /**
     * Lowercases the email and brings the phone number to a digits-only international form.
     */
    public static function normalize(Importers $importer, array $lead): array
    {
        $email = isset($lead['email']) ? mb_strtolower(trim($lead['email'])) : null;

        return [
            'name'   => trim($lead['name'] ?? '') ?: null,
            'email'  => $email ?: null,
            'phone'  => self::normalizePhone($lead['phone'] ?? null, $importer->config['default_country_code'] ?? null),
            'fields' => $lead['fields'] ?? [],
            'source' => $lead['source'] ?? $importer->source_type,
            'form'   => $lead['form'] ?? null,
            // Optional, set by sources whose every event must become its own opportunity (e.g. one per WhatsApp message):
            // external_id makes the idempotency hash unique per event, description replaces the answers dump.
            'external_id' => isset($lead['external_id']) ? (string) $lead['external_id'] : null,
            'description' => isset($lead['description']) ? (string) $lead['description'] : null,
        ];
    }

    /**
     * Digits only, E.164 style with a leading '+'. Local numbers (leading 0 or no country code) get the
     * importer's default country code; without one they are kept as typed digits.
     */
    public static function normalizePhone(?string $phone, ?string $defaultCountryCode): ?string
    {
        if (!$phone) {
            return null;
        }

        $hasPlus = str_starts_with(trim($phone), '+') || str_starts_with(trim($phone), '00');
        $digits = preg_replace('/\D+/', '', $phone);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (!$hasPlus && $defaultCountryCode) {
            $digits = $defaultCountryCode . ltrim($digits, '0');
        }

        return '+' . $digits;
    }

    /**
     * Idempotency key built from the lead identity only, so it works for every source.
     */
    private static function hash(Importers $importer, array $lead): string
    {
        $parts = [
            $importer->source_type,
            $lead['form'] ?? '',
            $lead['email'] ?? '',
            $lead['phone'] ?? '',
        ];

        // Only added when present, so the hash of leads without one (Facebook) stays exactly as it was and already
        // imported leads are not imported a second time.
        if (!empty($lead['external_id'])) {
            $parts[] = $lead['external_id'];
        }

        return hash('sha256', implode('|', $parts));
    }

    /**
     * Resolves pipeline/stage/type for this lead (per-form overrides win over importer defaults).
     */
    private static function mapping(Importers $importer, array $lead): array
    {
        $config = $importer->config ?? [];
        $form = ($lead['form'] && isset($config['forms'][$lead['form']])) ? $config['forms'][$lead['form']] : [];

        $pipelineUuid = $form['flow_pipeline_id'] ?? $config['flow_pipeline_id'] ?? null;
        $stageUuid = $form['flow_stage_id'] ?? $config['flow_stage_id'] ?? null;
        $stageName = $form['flow_stage_name'] ?? $config['flow_stage_name'] ?? null;

        $pipeline = $pipelineUuid
            ? Pipelines::withoutGlobalScopes()->where('uuid', $pipelineUuid)->first()
            : null;

        $stage = null;

        if ($pipeline) {
            $stageQuery = Stages::withoutGlobalScopes()->where('flow_pipeline_id', $pipeline->id);
            // Stage by uuid, else by name (case-insensitive), else the pipeline's first stage
            if ($stageUuid) {
                $stage = $stageQuery->where('uuid', $stageUuid)->first();
            } elseif ($stageName) {
                $stage = $stageQuery->whereRaw('lower(name) = ?', [mb_strtolower($stageName)])->first();

                // A configured stage name that does not exist must fail the lead, not land it in another stage
                if (!$stage) {
                    throw new \InvalidArgumentException('Stage "' . $stageName . '" not found in the configured pipeline.');
                }
            } else {
                $stage = $stageQuery->orderBy('position')->orderBy('id')->first();
            }
        }

        return [
            'pipeline'      => $pipeline,
            'stage'         => $stage,
            'type'          => $form['opportunity_type'] ?? $config['opportunity_type'] ?? 'sales',
            'name_template' => $form['opportunity_name_template'] ?? $config['opportunity_name_template'] ?? null,
            'dedupe_window' => (int) ($config['dedupe_window_hours'] ?? 24),
            // Importer tags plus the form's own tags (not an override: both apply)
            'tags'          => self::mergeTags((array) ($config['tags'] ?? []), (array) ($form['tags'] ?? [])),
        ];
    }

    /**
     * Identity match: email first, then phone number. Creates the user when nobody matches.
     */
    private static function findOrCreateUser(array $lead): Users
    {
        $user = null;

        if ($lead['email']) {
            $user = Users::withoutGlobalScope(AuthorizationScope::class)->where('email', $lead['email'])->first();
        }

        if (!$user && $lead['phone']) {
            $user = Users::withoutGlobalScope(AuthorizationScope::class)->where('phone_number', $lead['phone'])->first();
        }

        if (!$user) {
            $parts = $lead['name'] ? preg_split('/\s+/', $lead['name']) : [];
            $surname = count($parts) > 1 ? array_pop($parts) : null;

            $user = UsersService::createWithoutAccount([
                'name'      => $parts ? implode(' ', $parts) : ($lead['email'] ?? $lead['phone']),
                'surname'   => $surname,
                // fullname is a generated column; email is the unique key, phone-only leads get a placeholder
                'email'     => $lead['email'] ?? self::placeholderEmail($lead['phone']),
                'is_active' => true,
            ]);
        }

        // Fill the phone number if the user has none (never overwrite a verified one)
        if ($lead['phone'] && !$user->phone_number) {
            $user = UsersService::update($user->uuid, [
                'phone_number'             => $lead['phone'],
                'is_phone_number_verified' => false,
            ]);
        }

        return $user;
    }

    /**
     * IAM users are keyed by email; phone-only leads get a deterministic, non-routable address.
     */
    private static function placeholderEmail(string $phone): string
    {
        return 'lead-' . ltrim($phone, '+') . '@leads.invalid';
    }

    /**
     * @return array{0: IamAccounts, 1: bool} the account and whether this call created it
     */
    private static function findOrCreateAccount(Users $user): array
    {
        // Prefer the user's existing account, otherwise create the initial one
        $accountUser = AccountUsers::withoutGlobalScopes()
            ->where('iam_user_id', $user->id)
            ->orderBy('id')
            ->first();

        if ($accountUser) {
            $account = IamAccounts::withoutGlobalScopes()->find($accountUser->iam_account_id);

            if ($account) {
                return [$account, false];
            }
        }

        // The import creates its own opportunity for this lead. Mark the user so the "new account" lead-nurture job
        // (a second opportunity in the same pipeline) skips accounts created here; the job runs async, so the marker
        // has to be set before the account (and its created event) exists.
        Cache::put(self::SKIP_NURTURE_CACHE_PREFIX . $user->id, true, now()->addMinutes(10));

        return [IamAccountsService::createInitialAccount($user), true];
    }

    /**
     * Unique, non-empty, trimmed tags; first occurrence order is kept.
     *
     * @return string[]
     */
    private static function mergeTags(array ...$lists): array
    {
        $tags = [];

        foreach ($lists as $list) {
            foreach ($list as $tag) {
                $tag = is_scalar($tag) ? trim((string) $tag) : '';

                // Config is free-form JSON: cap tag length and count so a bad config cannot flood the tags column
                if ($tag !== '' && mb_strlen($tag) <= 100 && count($tags) < 20) {
                    $tags[$tag] = $tag;
                }
            }
        }

        return array_values($tags);
    }

    /**
     * Adds tags to a model that has a tags column without dropping the ones it already has.
     * The write goes through the given service callback; nothing is written when there is nothing new.
     */
    private static function applyTags($model, array $tags, callable $update): void
    {
        $merged = self::mergeTags((array) $model->tags, $tags);

        if ($tags && $merged !== array_values((array) $model->tags)) {
            $update($model->uuid, $merged);
        }
    }

    private static function findOrCreateCrmAccount(IamAccounts $iamAccount, array $lead): CrmAccounts
    {
        $crmAccount = CrmAccounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('iam_account_id', $iamAccount->id)
            ->first();

        if (!$crmAccount) {
            $crmAccount = AccountsService::create([
                'iam_account_id'      => $iamAccount->uuid,
                'office_phone_number' => $lead['phone'],
                'office_email'        => $lead['email'],
            ]);
        } elseif ($lead['phone'] && !$crmAccount->office_phone_number) {
            $crmAccount = AccountsService::update($crmAccount->uuid, ['office_phone_number' => $lead['phone']]);
        }

        return $crmAccount;
    }

    /**
     * A repeat submission inside the dedupe window must not open a second opportunity for the same customer.
     */
    private static function findRecentOpportunity(Importers $importer, CrmAccounts $crmAccount, array $mapping): ?Opportunities
    {
        if ($mapping['dedupe_window'] <= 0) {
            return null;
        }

        return Opportunities::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_id', $crmAccount->id)
            ->where('iam_account_id', $importer->iam_account_id)
            ->where('source', $importer->source_type)
            ->where('created_at', '>=', now()->subHours($mapping['dedupe_window']))
            ->first();
    }

    private static function createOpportunity(Importers $importer, array $lead, CrmAccounts $crmAccount, array $mapping): Opportunities
    {
        $owner = IamAccounts::withoutGlobalScopes()->find($importer->iam_account_id);

        // crm_campaign_id is deliberately omitted so the responsible-user assignment and notification run
        return OpportunitiesService::create([
            'name'           => self::opportunityName($lead, $mapping['name_template']),
            'description'    => $lead['description'] ?? ($lead['fields'] ? json_encode($lead['fields'], JSON_UNESCAPED_UNICODE) : null),
            'source'         => $importer->source_type,
            'type'           => $mapping['type'],
            'crm_account_id' => $crmAccount->uuid,
            'iam_account_id' => $owner->uuid,
            // Importer/form tags (config.tags, config.forms.<id>.tags)
            'tags'           => $mapping['tags'] ?: null,
        ]);
    }

    /**
     * Opportunity name from the importer's template ({name} = lead name, falling back to email/phone),
     * e.g. "FB {name}" -> "FB Jane Doe". Without a template: "<name> (<source>)".
     */
    private static function opportunityName(array $lead, ?string $template): string
    {
        $name = $lead['name'] ?? $lead['email'] ?? $lead['phone'];

        return $template
            ? trim(str_replace('{name}', $name, $template))
            : $name . ' (' . $lead['source'] . ')';
    }

    private static function createFlowItem(Opportunities $opportunity, IamAccounts $iamAccount, Importers $importer, array $mapping): void
    {
        if (!$mapping['stage']) {
            throw new \InvalidArgumentException('Flow pipeline has no stage to place the lead in.');
        }

        $owner = IamAccounts::withoutGlobalScopes()->find($importer->iam_account_id);
        $ownerUser = Users::withoutGlobalScopes()->find($importer->iam_user_id);

        // Same linkage as GenerateSalesCampaignOpportunities: object_type short form + opportunity id
        ItemsService::create([
            'flow_pipeline_id' => $mapping['pipeline']->uuid,
            'flow_stage_id'    => $mapping['stage']->uuid,
            'object_type'      => 'NextDeveloper\CRM\Opportunities',
            'object_id'        => $opportunity->id,
            'iam_account_id'   => $owner->uuid,
            'iam_user_id'      => $ownerUser->uuid,
        ]);
    }
}

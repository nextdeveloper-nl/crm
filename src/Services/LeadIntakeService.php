<?php

namespace NextDeveloper\CRM\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use NextDeveloper\CRM\Database\Models\Importers;
use NextDeveloper\CRM\Jobs\Importers\ImportLeadJob;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Public "lead intake" API: a website form or partner system posts a customer's information with a per-importer
 * secret token. The request is checked synchronously (token, payload, importer pipeline/stage) so the caller learns
 * about errors immediately, then the actual work (IAM user + account, CRM account, opportunity, flow item in the
 * importer's pipeline/stage) is queued through ImportLeadJob -> LeadImporterService.
 *
 * The endpoint uses a crm_importers row with source_type 'api' or 'ideasoft' (the Ideasoft website form). Its config follows LeadImporterService (flow_pipeline_id,
 * flow_stage_id / flow_stage_name, opportunity_type, tags, default_country_code, forms...). The token is shown once by
 * issueToken(); only its sha256 is stored in config.intake_token_hash.
 */
class LeadIntakeService
{
    public const SOURCE_TYPE = 'api';

    // The Ideasoft website form posts here too; its importer keeps source_type 'ideasoft' so the opportunities are
    // sourced (and deduplicated, tagged and routed to a pipeline) as Ideasoft leads, apart from generic api ones.
    public const SOURCE_TYPE_IDEASOFT = 'ideasoft';

    public const SOURCE_TYPES = [self::SOURCE_TYPE, self::SOURCE_TYPE_IDEASOFT];

    /**
     * Resolves the importer and verifies the token. Both failures return null so a caller cannot tell an unknown
     * importer from a wrong token.
     */
    public static function authenticate(string $importerUuid, ?string $token): ?Importers
    {
        $importer = Str::isUuid($importerUuid)
            ? Importers::withoutGlobalScopes()->where('uuid', $importerUuid)->whereIn('source_type', self::SOURCE_TYPES)->first()
            : null;

        $hash = $importer->config['intake_token_hash'] ?? null;

        if (!$importer || !$hash || !$token || !hash_equals($hash, hash('sha256', $token))) {
            return null;
        }

        return $importer;
    }

    /**
     * Validates, pre-checks and queues one lead.
     *
     * @return array{status: string, importer_id: string}
     * @throws \Illuminate\Validation\ValidationException for a bad payload
     * @throws \InvalidArgumentException when the importer cannot take the lead (inactive, no pipeline/stage...)
     */
    public static function submit(Importers $importer, array $payload): array
    {
        if ($importer->status !== 'active') {
            throw new \InvalidArgumentException('Importer is not active.');
        }

        $data = Validator::make($payload, [
            'name'        => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:32',
            'fields'      => 'nullable|array|max:50',
            'form'        => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
            'external_id' => 'nullable|string|max:255',
        ])->validate();

        // Only validated keys are passed on; source is always the importer's, never the caller's
        $lead = LeadImporterService::preflight($importer, $data + ['source' => $importer->source_type]);

        ImportLeadJob::dispatch($importer->id, $lead);

        return ['status' => 'queued', 'importer_id' => $importer->uuid];
    }

    /**
     * Creates a new token (replacing any previous one) and returns it in plain text; it cannot be read again.
     */
    public static function issueToken(Importers $importer): string
    {
        $token = Str::random(48);

        $config = $importer->config ?? [];
        $config['intake_token_hash'] = hash('sha256', $token);

        // Written through the service, as the owner of the importer (the command runs without a user)
        UserHelper::runAsAdmin(fn () => ImportersService::update($importer->uuid, ['config' => $config]));

        return $token;
    }
}

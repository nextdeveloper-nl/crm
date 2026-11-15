<?php

namespace NextDeveloper\CRM\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Importers;
use NextDeveloper\CRM\Services\AbstractServices\AbstractImportersService;

/**
 * This class is responsible from managing the data for Importers
 *
 * Class ImportersService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class ImportersService extends AbstractImportersService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * object_id may be sent as the linked object's uuid (public API) or as its integer id (internal callers);
     * it is stored as the integer id.
     */
    public static function create(array $data)
    {
        // A client can never supply the intake token hash; it is only ever set by issueToken()
        $data = self::withoutClientTokenHash(self::resolveLinkedObject($data));  // no stored importer yet

        $importer = parent::create($data);

        // Intake importers (api/ideasoft) get their token right away. The plain token is attached to the returned
        // model as a transient attribute so the create response can show it ONCE; it is never persisted (only its
        // sha256 lives in config.intake_token_hash) and the transformer keeps it out of the cache.
        if (in_array($importer->source_type, LeadIntakeService::SOURCE_TYPES, true)) {
            $token = LeadIntakeService::issueToken($importer);
            $importer = $importer->fresh();
            $importer->intake_token_plain = $token;
        }

        return $importer;
    }

    public static function update($id, array $data)
    {
        // On update the object_type may be omitted; the stored importer's type is then the one the id refers to.
        $data = self::resolveLinkedObject($data, $id);

        // A PATCH sends the whole config; keep the stored token hash so an unrelated edit does not kill the token
        if (array_key_exists('config', $data)) {
            $existing = Importers::where('uuid', $id)->first();
            $data = self::withoutClientTokenHash($data);

            if ($existing && !empty($existing->config['intake_token_hash'])) {
                $data['config'] = (array) $data['config'] + ['intake_token_hash' => $existing->config['intake_token_hash']];
            }
        }

        return parent::update($id, $data);
    }

    /**
     * Keys a background run may write on any importer (see updateSystemState).
     */
    private const SYSTEM_STATE_KEYS = ['cursor', 'last_synced_at', 'last_error', 'status', 'config'];

    /**
     * Bookkeeping written by background runs (the Facebook sync command, a Page disconnect or takeover): cursor,
     * last_synced_at, last_error, status and config.
     *
     * update() finds the row through the authorization scope, so a run without a user, or one running as an admin who is
     * not a member of the importer's account, cannot see another account's importer and fails with "We cannot find the
     * related object to update". This method looks the importer up without the scope and only accepts the keys above,
     * so it cannot be used to move an importer to another account or user. The role check of the observer is bypassed
     * for the same reason (there is no acting user); callers must already be trusted server-side code.
     * As in update(), the stored intake token hash always survives a config write.
     */
    public static function updateSystemState(string $uuid, array $data): Importers
    {
        $data = array_intersect_key($data, array_flip(self::SYSTEM_STATE_KEYS));

        $model = Importers::withoutGlobalScopes()->where('uuid', $uuid)->first();

        if (!$model) {
            throw new \InvalidArgumentException('The importer to update could not be found.');
        }

        if (array_key_exists('config', $data)) {
            $config = (array) $data['config'];
            unset($config['intake_token_hash']);

            if (!empty($model->config['intake_token_hash'])) {
                $config['intake_token_hash'] = $model->config['intake_token_hash'];
            }

            $data['config'] = $config;
        }

        \NextDeveloper\IAM\Helpers\UserHelper::withRolesCheckBypassed(fn () => $model->update($data));

        return $model->fresh();
    }

    /**
     * Stores a new intake token hash on the importer (the only way the hash is written). Goes straight to the abstract
     * service so the client-hash protection in update() does not apply.
     */
    public static function setIntakeTokenHash(Importers $importer, string $hash): Importers
    {
        $config = $importer->config ?? [];
        $config['intake_token_hash'] = $hash;

        return parent::update($importer->uuid, ['config' => $config]);
    }

    /**
     * Rotates the intake token of an api/ideasoft importer the caller may update. The plain token is returned once
     * and the previous token stops working immediately.
     *
     * @return array{intake_token: string, intake_url: string}
     */
    public static function rotateIntakeToken(string $uuid): array
    {
        $importer = Importers::where('uuid', $uuid)->first();

        if (!$importer) {
            throw new NotAllowedException('We cannot find the importer. Maybe you dont have the permission to update it?');
        }

        if (!in_array($importer->source_type, LeadIntakeService::SOURCE_TYPES, true)) {
            throw new \InvalidArgumentException('Only api and ideasoft importers have an intake token.');
        }

        return [
            'intake_token' => LeadIntakeService::issueToken($importer),
            'intake_url'   => self::intakeUrl($importer),
        ];
    }

    /**
     * Public URL the website form posts to; null for importers that have no intake endpoint.
     */
    public static function intakeUrl(Importers $importer): ?string
    {
        if (!in_array($importer->source_type, LeadIntakeService::SOURCE_TYPES, true)) {
            return null;
        }

        $host = config('leo.api_url', 'apiv4.plusclouds.com');
        $base = Str::startsWith($host, ['http://', 'https://']) ? rtrim($host, '/') : 'https://' . $host;

        return $base . '/public/lead-intake/' . $importer->uuid;
    }

    /**
     * Drops a client supplied intake_token_hash from a config payload.
     */
    private static function withoutClientTokenHash(array $data): array
    {
        if (isset($data['config']) && is_array($data['config'])) {
            unset($data['config']['intake_token_hash']);
        }

        return $data;
    }

    /**
     * Converts a uuid object_id into the integer id of the model named by object_type.
     * The lookup runs through the model's normal scopes, so a caller can only link objects they may see.
     */
    private static function resolveLinkedObject(array $data, ?string $existingImporterUuid = null): array
    {
        $typeSent = !empty($data['object_type']);
        $idSent   = array_key_exists('object_id', $data);

        // Nothing about the link is being written.
        if (!$typeSent && !$idSent) {
            return $data;
        }

        // Explicitly clearing the link.
        if ($idSent && $data['object_id'] === null) {
            return $data;
        }

        // Multi-tenancy: the EFFECTIVE (type, id) pair is what gets stored, so it is the pair that is checked. On an
        // update either half may be omitted and then comes from the stored importer; changing only the type (or only
        // the id) would otherwise point the old half at an object nobody verified. The lookup runs through the model's
        // normal scopes, so a caller can only link objects they may see.
        $stored = $existingImporterUuid ? Importers::where('uuid', $existingImporterUuid)->first() : null;

        $class = $typeSent ? $data['object_type'] : $stored?->object_type;
        $rawId = $idSent ? $data['object_id'] : $stored?->object_id;

        if ($rawId === null || $rawId === '') {
            return $data;   // a type with no id links nothing
        }

        // Only real Eloquent models; fail closed when the type is missing or not a model.
        if (!$class || !class_exists($class) || !is_subclass_of($class, Model::class)) {
            throw new \InvalidArgumentException('object_type must be a model class when object_id is given.');
        }

        // integer id (internal callers, or the stored id): check it is visible
        if (is_int($rawId) || (is_string($rawId) && ctype_digit($rawId))) {
            if (!$class::where('id', (int) $rawId)->exists()) {
                throw new \InvalidArgumentException('The linked object could not be found.');
            }

            return $data;
        }

        // public API: uuid, stored as the integer id
        if (is_string($rawId) && Str::isUuid($rawId)) {
            $object = $class::where('uuid', $rawId)->first();

            if (!$object) {
                throw new \InvalidArgumentException('The linked object could not be found.');
            }

            $data['object_id'] = $object->id;

            return $data;
        }

        throw new \InvalidArgumentException('object_id must be a uuid.');
    }

    /**
     * Public representation of object_id: the linked object's uuid (never the integer id).
     */
    public static function linkedObjectUuid(?string $class, ?int $id): ?string
    {
        if (!$class || !$id || !class_exists($class) || !is_subclass_of($class, Model::class)) {
            return null;
        }

        return $class::withoutGlobalScopes()->where('id', $id)->value('uuid');
    }
}
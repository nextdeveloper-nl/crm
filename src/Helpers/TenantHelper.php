<?php

namespace NextDeveloper\CRM\Helpers;

use Illuminate\Database\Eloquent\Model;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Tenant ownership helpers for CRM.
 *
 * CRM is multi-tenant: the tenant is the owner iam_account of a record
 * (iam_account_id on tenant-owned tables such as targets, campaigns,
 * email templates, importers). Several services must resolve records by UUID
 * with AuthorizationScope removed (the scope stops at the first matching
 * role and some roles are unscoped), so they must check ownership explicitly
 * through these helpers instead of trusting the UUID.
 */
class TenantHelper
{
    /**
     * Returns the record with the given UUID only if it is owned by the
     * current account. Returns null when it does not exist or belongs to
     * another tenant, so callers cannot tell the two cases apart (no UUID
     * probing across tenants).
     *
     * @param class-string<Model> $modelClass Model with uuid and iam_account_id columns
     */
    public static function findOwned(string $modelClass, ?string $uuid): ?Model
    {
        if (!$uuid) {
            return null;
        }

        return $modelClass::withoutGlobalScope(AuthorizationScope::class)
            ->where('uuid', $uuid)
            ->where('iam_account_id', UserHelper::currentAccount()->id)
            ->first();
    }

    /**
     * Same as findOwned() but throws ModelNotFoundException (404) when the
     * record is missing or owned by another tenant.
     *
     * @param class-string<Model> $modelClass
     */
    public static function findOwnedOrFail(string $modelClass, ?string $uuid): Model
    {
        $model = self::findOwned($modelClass, $uuid);

        if (!$model) {
            throw (new \Illuminate\Database\Eloquent\ModelNotFoundException())->setModel($modelClass, [$uuid]);
        }

        return $model;
    }

    /**
     * True when the given model instance is owned by the current account.
     */
    public static function isOwned(Model $model): bool
    {
        return (int) $model->iam_account_id === (int) UserHelper::currentAccount()->id;
    }
}

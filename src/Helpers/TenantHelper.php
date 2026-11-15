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
        // No uuid or no acting account: nothing is owned by "nobody".
        if (!$uuid || !UserHelper::currentAccount()) {
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
        $account = UserHelper::currentAccount();

        return $account !== null && (int) $model->iam_account_id === (int) $account->id;
    }

    /**
     * Master switch for tenant isolation (config crm.tenant_isolation.enabled, on by default). Needs the owner
     * column DDL applied; set CRM_TENANT_ISOLATION_ENABLED=false where it is not.
     */
    public static function isolationEnabled(): bool
    {
        return (bool) config('crm.tenant_isolation.enabled', true);
    }

    /**
     * The platform (operator) account id: crm.tenant_isolation.platform_account_id, falling back to
     * leo.current_account_id. Null when neither is configured (nobody is treated as the platform).
     */
    public static function platformAccountId(): ?int
    {
        $id = config('crm.tenant_isolation.platform_account_id') ?? config('leo.current_account_id');

        return ($id === null || $id === '') ? null : (int) $id;
    }

    public static function isPlatformAccount(int $accountId): bool
    {
        $platform = self::platformAccountId();

        return $platform !== null && $platform === $accountId;
    }

    /**
     * Owner (tenant) for a new tenant-owned record: the explicitly given tenant (importers and other system jobs
     * that run as admin must pass the importer's account) or the current account.
     */
    public static function resolveOwnerId(?int $explicitOwnerId = null): int
    {
        if ($explicitOwnerId !== null) {
            return $explicitOwnerId;
        }

        $account = UserHelper::currentAccount();

        // Never stamp a record with a made-up owner: without an acting account the caller must name the owner.
        if (!$account) {
            throw new \LogicException('No acting account to own the record; pass the owner explicitly.');
        }

        return (int) $account->id;
    }

    /**
     * Whether the caller may write the shared catalogs (industries, offerings, ...): the platform account, trusted
     * server-side callers that bypass the role check (seeders, importers running as admin) or isolation switched off.
     */
    public static function maySharedCatalogBeWritten(): bool
    {
        if (!self::isolationEnabled() || UserHelper::bypassRolesCheck()) {
            return true;
        }

        $account = UserHelper::currentAccount();

        return $account !== null && self::isPlatformAccount((int) $account->id);
    }

    /**
     * Fails with a 404-style exception unless the referenced parent record (a CRM account, opportunity, quote, ...)
     * is visible to the caller through the model's normal scopes (role + tenant). Creating a child record (call, note,
     * quote item, ...) for a parent the caller cannot see would plant data into another tenant's account, and the
     * generated services resolve the parent uuid to an id WITHOUT checking visibility. Trusted server-side callers
     * (importers running as admin) are not restricted.
     *
     * @param class-string<Model> $modelClass
     * @param int|string|null     $idOrUuid
     */
    public static function assertVisible(string $modelClass, $idOrUuid, bool $tenantBoundaryOnly = false): void
    {
        if ($idOrUuid === null || $idOrUuid === '' || UserHelper::bypassRolesCheck()) {
            return;
        }

        // $tenantBoundaryOnly: skip the role filter and keep the tenant boundary (TenantScope). Needed where the role
        // filter depends on the very row being created (a manager sees an account only once it manages it).
        $query = $tenantBoundaryOnly ? $modelClass::withoutGlobalScope(AuthorizationScope::class) : $modelClass::query();
        is_numeric($idOrUuid) ? $query->where('id', (int) $idOrUuid) : $query->where('uuid', $idOrUuid);

        if (!$query->exists()) {
            throw (new \Illuminate\Database\Eloquent\ModelNotFoundException())->setModel($modelClass, [$idOrUuid]);
        }
    }

    /**
     * Single guard for create AND update of child records (calls, meetings, notes, projects, opportunities, quotes,
     * quote items): every parent foreign key present in the payload must be visible to the caller, and an untrusted
     * caller can never choose the owning account. Defense in depth: the generated services resolve uuids with scoped
     * lookups, but nothing in them states that intent, and a future unscoped lookup would silently allow planting
     * records into (or re-parenting them onto) another tenant's account.
     *
     * @return array the payload, without iam_account_id for untrusted callers
     */
    public static function guardWrite(array $data): array
    {
        self::assertVisible(\NextDeveloper\CRM\Database\Models\Accounts::class, $data['crm_account_id'] ?? null);
        self::assertVisible(\NextDeveloper\CRM\Database\Models\Opportunities::class, $data['crm_opportunity_id'] ?? null);
        self::assertVisible(\NextDeveloper\CRM\Database\Models\Quotes::class, $data['crm_quote_id'] ?? null);

        if (!UserHelper::bypassRolesCheck()) {
            unset($data['iam_account_id']);
        }

        return $data;
    }
}

<?php

namespace NextDeveloper\CRM\Database\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;
use NextDeveloper\CRM\Helpers\TenantHelper;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Tenant boundary for CRM.
 *
 * AuthorizationScope picks ONE role and stops, and several CRM roles (sales admin, marketing manager) apply no
 * account filter at all, so it cannot be the tenant boundary. This scope is registered next to it in
 * crm.scopes.global and runs regardless of which role won.
 *
 * Fail closed: a crm_* table that is not classified in crm.tenant_isolation returns nothing to tenants.
 *
 * It restricts the tables listed in crm.tenant_isolation.* to rows owned by the current account: crm_accounts by
 * owner_iam_account_id, tenant-owned tables by their iam_account_id, link tables through their parent row. The platform account (the operator) is not restricted.
 *
 * Enabled by default (crm.tenant_isolation.enabled). The DDL (schemas/changes/2026_11_14_crm_accounts_owner.sql)
 * must be applied first, otherwise queries fail on the missing owner_iam_account_id column; set
 * CRM_TENANT_ISOLATION_ENABLED=false to turn the scope off.
 */
class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!TenantHelper::isolationEnabled()) {
            return;
        }

        $table = $model->getTable();

        $ownerTables   = (array) config('crm.tenant_isolation.owner_column_tables', []);
        $accountTables = (array) config('crm.tenant_isolation.account_column_tables', []);
        $parentTables  = (array) config('crm.tenant_isolation.parent_scoped_tables', []);

        $isOwner   = in_array($table, $ownerTables, true);
        $isAccount = array_key_exists($table, $accountTables);
        $isParent  = array_key_exists($table, $parentTables);

        // Global reference data is not tenant filtered.
        if (in_array($table, (array) config('crm.tenant_isolation.shared_tables', []), true)) {
            return;
        }

        $ownedCustomerTables = (array) config('crm.tenant_isolation.owned_customer_account_tables', []);
        $contactTables       = (array) config('crm.tenant_isolation.contact_tables', []);

        $isOwnedCustomer = array_key_exists($table, $ownedCustomerTables);
        $isContact       = in_array($table, $contactTables, true);

        $isClassified = $isOwner || $isAccount || $isParent || $isOwnedCustomer || $isContact;

        // Fail closed: a crm_* table that is not classified in config (a new table, or a platform-only view) returns
        // no rows to tenants. The platform account is never restricted.
        if (!$isClassified && !str_starts_with($table, 'crm_')) {
            return;
        }

        $account = UserHelper::currentAccount();

        if (TenantHelper::isPlatformAccount($account->id)) {
            return;
        }

        if (!$isClassified) {
            $builder->whereRaw('1 = 0');

            return;
        }

        if ($isOwner) {
            $builder->where($table . '.owner_iam_account_id', $account->id);
        }

        if ($isAccount) {
            $builder->where($table . '.' . $accountTables[$table], $account->id);
        }

        if ($isOwnedCustomer) {
            $column = $table . '.' . $ownedCustomerTables[$table];

            $builder->where(function (Builder $query) use ($column, $account) {
                $query->where($column, $account->id)
                    ->orWhereIn($column, self::ownedCustomerAccountIds($account->id));
            });
        }

        if ($isContact) {
            $builder->where(function (Builder $query) use ($table, $account) {
                // people who belong to the tenant or to a customer it owns
                $query->whereIn(
                    $table . '.iam_user_id',
                    DB::table('iam_account_user')->select('iam_user_id')->where(function ($members) use ($account) {
                        $members->where('iam_account_id', $account->id)
                            ->orWhereIn('iam_account_id', self::ownedCustomerAccountIds($account->id));
                    })
                )
                // contacts the tenant manages
                ->orWhereIn(
                    $table . '.id',
                    DB::table('crm_user_managers')->select('crm_user_id')
                        ->where('iam_account_id', $account->id)->whereNull('deleted_at')
                )
                // contacts in one of the tenant's target lists
                ->orWhereIn(
                    $table . '.id',
                    DB::table('crm_target_users')->select('crm_user_id')->whereIn(
                        'crm_target_id',
                        DB::table('crm_targets')->select('id')->where('iam_account_id', $account->id)
                    )
                );
            });
        }

        if ($isParent) {
            // Link tables: visible when the parent row (campaign / target) belongs to the tenant. Plain query
            // builder, so the parent is checked by account here and not by another scope.
            [$foreignKey, $parentTable, $parentOwnerColumn] = $parentTables[$table];

            $builder->whereIn(
                $table . '.' . $foreignKey,
                DB::table($parentTable)->select('id')->where($parentOwnerColumn, $account->id)
            );
        }
    }

    /**
     * Customer iam_account ids the tenant owns a CRM record for (crm_accounts.owner_iam_account_id = tenant).
     * Plain query builder: it is a subquery of the scope itself and must not recurse into the scope.
     */
    private static function ownedCustomerAccountIds(int $tenantAccountId): \Illuminate\Database\Query\Builder
    {
        return DB::table('crm_accounts')
            ->select('iam_account_id')
            ->where('owner_iam_account_id', $tenantAccountId)
            ->whereNull('deleted_at');
    }
}

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

        if (!$isOwner && !$isAccount && !$isParent) {
            return;
        }

        $account = UserHelper::currentAccount();

        // The operator sees every tenant; everybody else only their own records.
        if (TenantHelper::isPlatformAccount($account->id)) {
            return;
        }

        if ($isOwner) {
            $builder->where($table . '.owner_iam_account_id', $account->id);
        }

        if ($isAccount) {
            $builder->where($table . '.' . $accountTables[$table], $account->id);
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
}

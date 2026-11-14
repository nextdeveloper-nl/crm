<?php

namespace NextDeveloper\CRM\Database\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use NextDeveloper\CRM\Helpers\TenantHelper;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Tenant boundary for CRM.
 *
 * AuthorizationScope picks ONE role and stops, and several CRM roles (sales admin, marketing manager) apply no
 * account filter at all, so it cannot be the tenant boundary. This scope is registered next to it in
 * crm.scopes.global and runs regardless of which role won.
 *
 * It restricts the tables listed in crm.tenant_isolation.owner_column_tables to rows whose
 * owner_iam_account_id is the current account. The platform account (the operator) is not restricted.
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

        if (!in_array($table, (array) config('crm.tenant_isolation.owner_column_tables', []), true)) {
            return;
        }

        $account = UserHelper::currentAccount();

        // The operator sees every tenant; everybody else only their own records.
        if (TenantHelper::isPlatformAccount($account->id)) {
            return;
        }

        $builder->where($table . '.owner_iam_account_id', $account->id);
    }
}

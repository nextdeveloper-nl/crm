<?php

namespace NextDeveloper\CRM\Database\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Limits crm_sales_people_perspective to the sales staff of the CURRENT account.
 *
 * The view lists every user holding a sales role (roles are global, iam_role_user has no account), so without this
 * the endpoint returned every sales person on the platform. TenantScope does not help here: it never restricts the
 * platform account and, for tenants, also lets owned customers' people through.
 *
 * Membership is checked against iam_account_user, not the view's iam_account_id column: the view uses
 * DISTINCT ON (user) so that column is just one arbitrary account of a multi-account user.
 *
 * Plain query builder subquery on purpose, so it cannot recurse into other scopes.
 */
class SalesPeopleScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $account = UserHelper::currentAccount();

        // No acting account: nothing to show (fail closed).
        if (!$account) {
            $builder->whereRaw('1 = 0');

            return;
        }

        $builder->whereIn(
            $model->getTable() . '.iam_user_id',
            DB::table('iam_account_user')
                ->select('iam_user_id')
                ->where('iam_account_id', $account->id)
        );
    }
}

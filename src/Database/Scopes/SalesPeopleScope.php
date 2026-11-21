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
 * Plain query builder on purpose, so it cannot recurse into other scopes.
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

        // The member ids are fetched first and passed as literals, not as a subquery. The view is built on
        // DISTINCT ON (iam_users.id): PostgreSQL pushes a plain "iam_user_id IN (1, 2, ...)" down into it, so only those
        // users are computed, but it does NOT push a sub-select down, so the whole platform's sales staff was built
        // (joins over iam_role_user / iam_account_user) before being filtered, which hung the endpoint in production.
        $memberIds = DB::table('iam_account_user')
            ->where('iam_account_id', $account->id)
            ->pluck('iam_user_id')
            ->unique()
            ->values()
            ->all();

        // Nobody in the account: nothing to show (and an empty IN () is not valid for every driver).
        if (!$memberIds) {
            $builder->whereRaw('1 = 0');

            return;
        }

        $builder->whereIn($model->getTable() . '.iam_user_id', $memberIds);
    }
}

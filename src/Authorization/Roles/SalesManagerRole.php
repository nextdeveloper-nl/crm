<?php

namespace NextDeveloper\CRM\Authorization\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Database\Models\UserManagers;
use NextDeveloper\IAM\Authorization\Roles\AbstractRole;
use NextDeveloper\IAM\Authorization\Roles\IAuthorizationRole;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;

class SalesManagerRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'sales-manager';

    public const LEVEL = 120;

    public const DESCRIPTION = 'Sales Manager';

    public const DB_PREFIX = 'crm';

    /**
     * Applies basic member role sql for Eloquent
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(
            $model->getTable() == 'crm_accounts_perspective'
        ) {
            $builder->whereRaw('iam_account_id IN       (
                select iam_account_id from crm_accounts_perspective where id in (
                    select crm_account_id from crm_account_managers cam where cam.iam_account_id = ' . UserHelper::currentAccount()->id . '
                )
            )');

            return;
        }

        if(
            $model->getTable() == 'crm_opportunities_perspective' ||
            $model->getTable() == 'crm_opportunities'
        ) {
            $builder->whereRaw('crm_account_id IN (
                    select crm_account_id from crm_account_managers cam where cam.iam_account_id = ' . UserHelper::currentAccount()->id . '
                )');

            return;
        }

        if($model->getTable() == 'crm_accounts') {
            /**
             * Here user will be able to list all models, because by default, sales manager can see everybody.
             */
            $ids = AccountManagers::withoutGlobalScopes()
                ->where('iam_account_id', UserHelper::currentAccount()->id)
                ->pluck('crm_account_id');

            $builder->whereIn('id', $ids);

            return;
        }

        if($model->getTable() == 'crm_users_perspective') {
            $builder->whereRaw('where id in (
                select cum.crm_user_id from crm_user_managers cum where (cum.iam_user_id = ' . UserHelper::currentAccount()->id . ')
                )
            )');
            return;
        }

        if($model->getTable() == 'crm_users') {
            $ids = UserManagers::withoutGlobalScopes()
                ->where('iam_account_id', UserHelper::currentAccount()->id)
                ->pluck('crm_user_id');

            $builder->whereIn('id', $ids);
            return;
        }
    }

    public function checkPrivileges(Users $users = null)
    {
        //return UserHelper::hasRole(self::NAME, $users);
    }

    public function getModule()
    {
        return 'crm';
    }

    public function allowedOperations() :array
    {
        return [
            'crm_accounts:read',
            'crm_accounts:create',
            'crm_accounts:update',
            'crm_users:read',
            'crm_users:create',
            'crm_users:update',
            'crm_account_managers:read',
            'crm_account_managers:create',
            'crm_account_managers:delete',
            'crm_account_perspectives:read',
            'crm_account_users_perspective:read',
            'crm_ideal_customer_profiles:read',
            'crm_ideal_customer_profiles:create',
            'crm_ideal_customer_profiles:update',
            'crm_ideal_customer_profiles:delete',
            'crm_opportunities:read',
            'crm_opportunities:create',
            'crm_opportunities:update',
            'crm_opportunities:delete',
            'crm_user_managers:read',
            'crm_user_managers:create',
            'crm_user_managers:delete',
            'crm_user_perspectives:read',
            'crm_quotes:read',
            'crm_quotes:create',
            'crm_quotes:update',
            'crm_quotes:delete',
            'crm_quote_lines:read',
            'crm_quote_lines:create',
            'crm_quote_lines:update',
            'crm_quote_lines:update',
            'crm_opportunities:read',
            'crm_opportunities:create',
            'crm_opportunities:update',
            'crm_opportunities:delete',
            'crm_calls:read',
            'crm_calls:create',
            'crm_calls:update',
            'crm_calls:delete',
            'crm_emails:read',
            'crm_emails:create',
            'crm_emails:update',
            'crm_emails:delete',
            'crm_tasks:read',
            'crm_tasks:create',
            'crm_tasks:update',
            'crm_tasks:delete',
            'crm_notes:read',
            'crm_notes:create',
            'crm_notes:update',
            'crm_notes:delete',
            'crm_meetings:read',
            'crm_meetings:create',
            'crm_meetings:update',
            'crm_meetings:delete',
        ];
    }

    public function checkUpdatePolicy(Model $model, Users $users) : bool
    {
        if($model->getTable() == 'crm_accounts') {
            $amIManager = AccountManagers::withoutGlobalScopes()
                ->where('iam_account_id', UserHelper::currentAccount()->id)
                ->where('crm_account_id', $model->id)
                ->first();

            if (config('leo.debug.authorization_scope'))
                Log::info('SalesManagerRole::checkUpdatePolicy', ['amIManager' => $amIManager]);

            return $amIManager !== null;
        }

        if($model->getTable() == 'crm_users') {
            $amIManager = UserManagers::withoutGlobalScopes()
                ->where('iam_user_id', UserHelper::currentUser()->id)
                ->where('crm_user_id', $model->id)
                ->first();

            if (config('leo.debug.authorization_scope'))
                Log::info('SalesManagerRole::checkUpdatePolicy', ['amIManager' => $amIManager]);

            return $amIManager !== null;
        }

        if(property_exists('iam_account_id', $model)) {
            if($model->iam_account_id == UserHelper::currentAccount()->id)
                return true;
        }

        if($model->iam_user_id == $users->id)
            return true;

        return false;
    }


    public function getLevel(): int
    {
        return self::LEVEL;
    }

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function canBeApplied($column)
    {
        if(self::DB_PREFIX === '*') {
            return true;
        }

        if(Str::startsWith($column, self::DB_PREFIX)) {
            return true;
        }

        return false;
    }

    public function getDbPrefix()
    {
        return self::DB_PREFIX;
    }

    public function checkRules(Users $users): bool
    {
        // TODO: Implement checkRules() method.
    }
}

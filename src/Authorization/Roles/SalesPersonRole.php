<?php

namespace NextDeveloper\CRM\Authorization\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\IAM\Authorization\Roles\AbstractRole;
use NextDeveloper\IAM\Authorization\Roles\IAuthorizationRole;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;

class SalesPersonRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'sales-person';

    public const LEVEL = 150;

    public const DESCRIPTION = 'Account Manager';

    public const DB_PREFIX = 'crm';

    /**
     * Applies basic member role sql
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if($model->getTable() == 'crm_accounts_perspective') {
            $builder->whereRaw('iam_account_id IN       (
                select distinct iam_account_id from crm_accounts_perspective where id in (
                    select distinct crm_account_id from crm_account_managers cam where cam.iam_user_id = ' . UserHelper::me()->id . '
                )
            )');

            return;
        }

        if($model->getTable() == 'crm_users_perspective') {
            $builder->whereRaw('where id in (
                select cum.crm_user_id from crm_user_managers cum where (cum.iam_user_id = ' . UserHelper::me()->id . ')
                )
            )');
            return;
        }

        /**
         * Here the user will only be able to run this query only if the table name starts with 'crm_*' and
         * the owner of the model is the user itself.
         */
        $builder->where('iam_user_id', UserHelper::me()->id);
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

    public function checkUpdatePolicy(Model $model, Users $users) : bool
    {
        $amIManager = AccountManagers::withoutGlobalScopes()
            ->where('iam_user_id', UserHelper::currentUser()->id)
            ->where('iam_account_id', UserHelper::currentAccount()->id)
            ->where('crm_account_id', $model->id)
            ->first();

        return $amIManager !== null;
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
            'crm_account_perspectives:read',
            'crm_account_users_perspective:read',
            'crm_ideal_customer_profiles:read',
            'crm_ideal_customer_profiles:create',
            'crm_ideal_customer_profiles:update',
            'crm_ideal_customer_profiles:delete',
            'crm_opportunities:read',
            'crm_opportunities:create',
            'crm_opportunities:update',
            'crm_user_managers:read',
            'crm_user_managers:create',
            'crm_user_managers:update',
            'crm_user_perspectives:read',
            'crm_quotes:read',
            'crm_quotes:create',
            'crm_quotes:update',
            'crm_opportunites:read',
            'crm_opportunites:create',
            'crm_opportunites:update',
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
            'crm_meetings:delete'
        ];
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

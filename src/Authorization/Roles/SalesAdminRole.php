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

class SalesAdminRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'sales-admin';

    public const LEVEL = 100;

    public const DESCRIPTION = 'Sales Admin who has full access to CRM module.';

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
        // This role can see all
    }

    public function checkPrivileges(Users $users = null)
    {
        //return UserHelper::hasRole(self::NAME, $users);
    }

    public function checkUpdatePolicy(Model $model, Users $user): bool
    {
        return true;
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
            'crm_users_perspective:read',
            'crm_quotes:read',
            'crm_quotes:create',
            'crm_quotes:update',
            'crm_quotes:delete',
            'crm_quote_items:read',
            'crm_quote_items:create',
            'crm_quote_items:update',
            'crm_quote_items:delete',
            'crm_opportunities:read',
            'crm_opportunities:create',
            'crm_opportunities:update',
            'crm_opportunities:delete',
            'crm_offerings:read',
            'crm_offerings:create',
            'crm_offerings:update',
            'crm_offerings:delete',
            'crm_industries:read',
            'crm_industries:create',
            'crm_industries:update',
            'crm_industries:delete',
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

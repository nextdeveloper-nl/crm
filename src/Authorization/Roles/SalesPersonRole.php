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
        /**
         * Here the user will only be able to run this query only if the table name starts with 'crm_*' and
         * the owner of the model is the user itself.
         */

        $ids = AccountManagers::withoutGlobalScopes()
            ->where('iam_account_id', UserHelper::currentAccount()->id)
            ->where('iam_user_id', UserHelper::currentUser()->id)
            ->pluck('crm_account_id');

        $builder->whereIn('crm_account_id', $ids);
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

    public function getModule()
    {
        return 'crm';
    }

    public function allowedOperations() :array
    {
        return [
            'crm_accounts:read',
            'crm_users:read',
            'crm_account_managers:read',
            'crm_account_perspectives:read',
            'crm_opportunities:read',
            'crm_opportunities:create',
            'crm_opportunities:update',
            'crm_user_managers:read',
            'crm_user_perspectives:read',
            'crm_quotes:read',
            'crm_quotes:create',
            'crm_quotes:update',
            'crm_opportunites:read',
            'crm_opportunites:create',
            'crm_opportunites:update',
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

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

class BusinessDevelopmentRepresentative extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'business-development-representative';

    public const LEVEL = 130;

    public const DESCRIPTION = 'Business Development Representative, BDR';

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
        if($model->getTable() == 'crm_accounts_perspective' || $model->getTable() == 'crm_accounts') {
            $builder->whereRaw('id IN       (
                select distinct crm_account_id from crm_account_managers cam where cam.iam_account_id = ' . UserHelper::currentAccount()->id . '
            )');

            return;
        }

        if($model->getTable() == 'crm_users_perspective') {
            $builder->whereRaw('id in (
                select cum.crm_user_id from crm_user_managers cum where (cum.iam_account_id = ' . UserHelper::currentAccount()->id . ')
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
        return array_merge((new SalesPersonRole())->allowedOperations(), [

        ]);
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

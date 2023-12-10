<?php

namespace NextDeveloper\IAM\Authorization\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\IAM\Helpers\UserHelper;

class AccountManagerRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'account-manager';

    public const LEVEL = 200;

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
    }

    /**
     * This only runs when the user is trying to reach its own accounts, or the accounts that he wants to reach
     *
     * With this filter, he can only reach to accounts;
     * - owned by him
     * - accounts that he is in
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function iamAccountTable(Builder $builder, Model $model)
    {

    }

    /**
     * He only reached to users which are in the same team as they are. But user sees all people in all teams.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function iamUserTable(Builder $builder, Model $model)
    {

    }
}

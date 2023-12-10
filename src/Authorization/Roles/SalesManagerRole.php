<?php

namespace NextDeveloper\IAM\Authorization\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\IAM\Helpers\UserHelper;

class SalesManagerRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'sales-manager';

    public const LEVEL = 190;

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
         * Here user will be able to list all models, because by default, sales manager can see everybody.
         */
    }
}

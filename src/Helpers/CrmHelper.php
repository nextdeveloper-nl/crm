<?php

namespace Helpers;

use NextDeveloper\IAM\Database\Models\Accounts;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

class CrmHelper
{
    public static function getUserOfObjectOwner($object)
    {
        return Users::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $object->iam_user_id)
            ->first();
    }

    public static function getTeamOwnerOfObject($object)
    {
        return Accounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $object->iam_account_id)
            ->first();
    }

    public static function getCustomerByCrmAccountId($id)
    {
        return Accounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $id)
            ->first();
    }

    public static function getCustomerByCrmAccount(\NextDeveloper\CRM\Database\Models\Accounts $account)
    {
        return self::getCustomerByCrmAccountId($account->id);
    }
}

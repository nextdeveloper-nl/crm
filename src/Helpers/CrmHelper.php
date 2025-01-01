<?php

namespace Helpers;

use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\IAM\Database\Models\Accounts;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\CRM\Database\Models\Accounts as CrmAccounts;
use NextDeveloper\IAM\Helpers\UserHelper;

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
        $crmAccount = CrmAccounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $id)
            ->first();

        return UserHelper::getAccountById($crmAccount->iam_account_id);
    }

    public static function getCustomerByCrmAccount(CrmAccounts $account)
    {
        return self::getCustomerByCrmAccountId($account->id);
    }

    public static function getAccountManagerAccount(CrmAccounts $account) : ?Accounts
    {
        $accountManager = AccountManagers::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_id', $account->id)
            ->first();

        return UserHelper::getAccountById($accountManager->iam_account_id);
    }
}

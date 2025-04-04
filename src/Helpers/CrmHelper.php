<?php

namespace Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\IAM\Database\Models\Accounts;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\CRM\Database\Models\Accounts as CrmAccounts;
use NextDeveloper\IAM\Helpers\UserHelper;

class CrmHelper
{
    public static function getAccountManagers(\NextDeveloper\CRM\Database\Models\Accounts $account) : Collection
    {
        $managers = AccountManagers::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_id', $account->id)
            ->pluck('iam_user_id');

        $users = Users::withoutGlobalScope(AuthorizationScope::class)
            ->whereIn('id', $managers)
            ->get();

        return $users;
    }

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
        $crmAccount = null;

        if(Str::isUuid($id)) {
            $crmAccount = CrmAccounts::withoutGlobalScope(AuthorizationScope::class)
                ->where('uuid', $id)
                ->first();
        } else {
            $crmAccount = CrmAccounts::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', $id)
                ->first();
        }

        if(!$crmAccount)
            return null;

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

    public static function getAccountOwner(Accounts|int $account) : Users
    {
        if(is_int($account)) {
            $account = Accounts::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', $account)
                ->first();
        }

        return Users::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $account->iam_user_id)
            ->first();
    }
}

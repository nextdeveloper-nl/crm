<?php

namespace Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use NextDeveloper\Commons\Helpers\StateHelper;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Services\UsersService;
use NextDeveloper\IAM\Database\Models\Accounts;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\CRM\Database\Models\Accounts as CrmAccounts;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\CRM\Database\Models\Users as CrmUsers;

class CrmHelper
{
    public static function addAccountManager(\NextDeveloper\CRM\Database\Models\Accounts $account, Accounts|int $iamAccount, Users|int $iamUser)
    {
        return AccountManagers::create([
            'crm_account_id' => $account->id,
            'iam_account_id' => is_int($iamAccount) ? $iamAccount : $iamAccount->id,
            'iam_user_id' => is_int($iamUser) ? $iamUser : $iamUser->id
        ]);
    }

    public static function getCrmAccount(Accounts|int $account)
    {
        if(is_int($account)) {
            return \NextDeveloper\CRM\Database\Models\Accounts::withoutGlobalScope(AuthorizationScope::class)
                ->where('iam_account_id', $account)
                ->first();
        }

        return \NextDeveloper\CRM\Database\Models\Accounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('iam_account_id', $account->id)
            ->first();
    }

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

    public static function getAccountOwner(Accounts|int $account) : ?Users
    {
        if(is_int($account)) {
            $account = Accounts::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', $account)
                ->first();
        }

        $user = Users::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $account->iam_user_id)
            ->first();

        if(!$user) {
            StateHelper::setState($account, 'user_not_found', true);
            Log::error('' . __METHOD__ . ' | Cannot find the user for account: ' . $account->id);

            return null;
        }

        return Users::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $account->iam_user_id)
            ->first();
    }

    public static function getCrmUserOfIamUser(Users|int $user) : ?CrmUsers
    {
        if(is_int($user)) {
            $user = Users::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', $user)
                ->first();
        }

        if(!$user) {
            StateHelper::setState($user, 'iam_user_not_found', true);
            Log::error('' . __METHOD__ . ' | Cannot find the user for user: ' . $user->id);

            return null;
        }

        $crmUser = CrmUsers::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $user->id)
            ->first();

        if(!$crmUser) {
            StateHelper::setState($user, 'crm_user_not_found', true);
            Log::error('' . __METHOD__ . ' | Cannot find the crm user for user: ' . $user->id);

            return null;
        }

        return $crmUser;
    }
}

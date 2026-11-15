<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Services\AbstractServices\AbstractAccountManagersService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\CRM\Database\Models\AccountsPerspective;

/**
 * This class is responsible from managing the data for AccountManagers
 *
 * Class AccountManagersService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class AccountManagersService extends AbstractAccountManagersService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function create($data) {
        // Multi-tenancy: a tenant may only appoint managers inside its own account, for CRM accounts it can see, and
        // only people who are members of that account. (The old WatchAccount flow wrote rows for the customer's account.)
        if (!UserHelper::bypassRolesCheck()) {
            $current = UserHelper::currentAccount();
            $targetAccount = UserHelper::getAccountById($data['iam_account_id'] ?? $current->id);

            if (!$targetAccount || (int) $targetAccount->id !== (int) $current->id) {
                throw new \NextDeveloper\Commons\Exceptions\NotAllowedException('Account managers can only be appointed inside your own account.');
            }

            \NextDeveloper\CRM\Helpers\TenantHelper::assertVisible(Accounts::class, $data['crm_account_id'] ?? null, true);
        }

        $user = UserHelper::getUserWithId($data['iam_user_id']);
        $account = UserHelper::getAccountById($data['iam_account_id']);

        //  Here we are taking without authorization because just below we need to create the auth rule. And the auth rule is not created yet.
        $crmAccount = Accounts::withoutGlobalScope(AuthorizationScope::class)->where('id', $data['crm_account_id'])->first();

        //  Checking if we have a duplicate
        $duplicate = AccountManagers::withoutGlobalScope(AuthorizationScope::class)
            ->where([
                'crm_account_id' => $crmAccount->id,
                'iam_account_id' => $account->id,
                'iam_user_id' => $user->id,
            ])
            ->first();

        if($duplicate) {
            return $duplicate;
        }

        // Removed the generic "Assigned as account manager" email sent via Communicate (sender "Example").
        // The branded "PlusClouds CRM" email (App\Envelopes\CRM\Accounts\AssignedAsAccountManager) is the only one sent now.
        return parent::create($data);
    }

    public static function assignAccountManagerToCrmAccount($crmAccount, $iamAccountId, $iamUserId) {
        $existing = AccountManagers::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_id', $crmAccount->id)
            ->where('iam_account_id', $iamAccountId)
            ->where('iam_user_id', $iamUserId)
            ->first();

        if ($existing) {
            return $existing;
        }

        $data = [
            'crm_account_id' => $crmAccount->id,
            'iam_account_id' => $iamAccountId,
            'iam_user_id' => $iamUserId
        ];

        return self::create($data);
    }
}

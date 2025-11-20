<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\Communication\Helpers\Communicate;
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
        $user = UserHelper::getUserWithId($data['iam_user_id']);
        $account = UserHelper::getAccountById($data['iam_account_id']);
        $crmAccount = Accounts::where('id', $data['crm_account_id'])->first();

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

        $user = UserHelper::getUserWithId($data['iam_user_id']);

        $crmAccount = AccountsPerspective::where('id', $data['crm_account_id'])->first();

        (new Communicate($user))->sendNotification(
            subject: 'Assigned as account manager',
            message: 'You are assigned as an account manager to the account namely:' . $crmAccount->name
        );

        return parent::create($data);
    }
}

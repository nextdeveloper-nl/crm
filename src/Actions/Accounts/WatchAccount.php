<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * Class WatchAccount
 *
 * This class handles the action of adding the current user as an Account Manager for a specified account.
 *
 * @package NextDeveloper\CRM\Actions\Accounts
 */
class WatchAccount extends AbstractAction
{
    /**
     * Events associated with this action.
     */
    public const EVENTS = [
        'watching:NextDeveloper\CRM\Accounts'
    ];

    /**
     * WatchAccount constructor.
     *
     * @param Accounts $accounts The account model instance.
     * @throws NotAllowedException If the action is not allowed.
     */
    public function __construct(Accounts $accounts)
    {
        $this->model = $accounts;
        parent::__construct(null);
    }

    /**
     * Handles the process of watching an account.
     *
     * This function performs the following steps:
     * 1. Sets initial progress.
     * 2. Check if the account exists.
     * 3. Check if the account has an associated user.
     * 4. Check if the user exists.
     * 5. Check if the account manager exists.
     * 6. Create an account manager if it does not exist.
     * 7. Fires an event indicating the account is being watched.
     * 8. Set the process as finished.
     */
    public function handle(): void
    {
        // Set initial progress
        $this->setProgress(0, 'Starting to watch account');

        // Check if an account exists
        $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::withoutGlobalScope(AuthorizationScope::class)
            ->find($this->model->iam_account_id);

        if (!$iamAccount) {
            $this->setProgress(22, 'We could not find the iam account associated with this account');
            return;
        }

        // Check if an account has a user
        if (!$iamAccount->iam_user_id) {
            $this->setProgress(30, 'Iam account does not have a user associated with it');
            return;
        }

        // Check if user exists
        $iamAccountUser = \NextDeveloper\IAM\Database\Models\Users::withoutGlobalScope(AuthorizationScope::class)
            ->find($iamAccount->iam_user_id);

        if (!$iamAccountUser) {
            $this->setProgress(50, 'We could not find the iam user associated with this iam account');
            return;
        }

        // Check if the account manager exists
        $this->setProgress(70, 'Checking if account manager exists');

        // Create account manager if it does not exist
        $accountManager = AccountManagers::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_id', $this->model->id)
            ->where('iam_user_id', $iamAccountUser->id)
            ->where('iam_account_id', $iamAccount->id)
            ->first();


        if (!$accountManager) {
            $accountManager = AccountManagers::withoutGlobalScope(AuthorizationScope::class)
                ->create([
                    'crm_account_id'    => $this->model->id,
                    'iam_user_id'       => $iamAccountUser->id,
                    'iam_account_id'    => $iamAccount->id
                ]);

            $this->setProgress(80, 'Account manager created');
        }

        // Fire event indicating the account is being watched
        Events::fire('watching', $accountManager);

        // Set the process as finished
        $this->setFinished();
    }
}

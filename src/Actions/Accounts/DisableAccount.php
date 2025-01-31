<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Database\Models\Accounts as IAMAccounts;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Class DisableAccount
 *
 * This action disables an IAM account associated with a CRM account, marking the account as inactive.
 *
 * @package NextDeveloper\CRM\Actions\Accounts
 */
class DisableAccount extends AbstractAction
{
    /**
     * Events associated with this action.
     */
    public const EVENTS = [
        'disabled:NextDeveloper\CRM\Accounts'
    ];

    /**
     * DisableAccount constructor.
     *
     * Initializes the action with the given CRM account.
     *
     * @param Accounts $accounts The account model instance.
     * @throws NotAllowedException If the action is not allowed.
     */
    public function __construct(Accounts $accounts)
    {
        $this->model = $accounts;
        parent::__construct();
    }

    /**
     * Handles the process of disabling an account.
     *
     * This function performs the following steps:
     * 1. Sets initial progress.
     * 2. Check if the IAM account exists.
     * 3. Disables the IAM account.
     * 4. Fires an event indicating the account has been disabled.
     * 5. Set the process as finished.
     */
    public function handle(): void
    {
        // Set initial progress
        $this->setProgress(0, 'Starting to disable account');

        // Check if the IAM account exists
        $iamAccount = IAMAccounts::withoutGlobalScope(AuthorizationScope::class)
            ->find($this->model->iam_account_id);

        if (!$iamAccount) {
            $this->setProgress(50, 'IAM account associated with this CRM account not found');
            return;
        }

        $this->setProgress(25, 'Disabling account');

        //  Disabling CRM account
        $this->model->update([
            'is_disabled' =>  true,
            'disabling_reason' => $this->model->disabling_reason . ' **Disabled by: ' . UserHelper::me()->fullname . '**'
        ]);
//
//        // Disable the account
//        $iamAccount->update(['is_active' => false]);

        $this->setProgress(75, 'Crm account disabled successfully');

        // Fire the 'disabled' event
        Events::fire('disabled:NextDeveloper\CRM\Accounts', $this->model);

        // Set the process as finished
        $this->setFinished();
    }
}

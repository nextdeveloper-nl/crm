<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Database\Models\Accounts as IAMAccounts;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * Class EnableAccount
 *
 * This action enables an IAM account associated with a CRM account, ensuring the account is active.
 *
 * @package NextDeveloper\CRM\Actions\Accounts
 */
class EnableAccount extends AbstractAction
{
    /**
     * Events associated with this action.
     */
    public const EVENTS = [
        'enabled:NextDeveloper\IAM\Accounts'
    ];

    /**
     * EnableAccount constructor.
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
     * Handles the process of enabling an account.
     *
     * This function performs the following steps:
     * 1. Sets initial progress.
     * 2. Check if the IAM account exists.
     * 3. Enable the IAM account.
     * 4. Fires an event indicating the account has been enabled.
     * 5. Set the process as finished.
     */
    public function handle(): void
    {
        // Set initial progress
        $this->setProgress(0, 'Starting to enable account');

        // Check if the IAM account exists
        $iamAccount = IAMAccounts::withoutGlobalScope(AuthorizationScope::class)
            ->find($this->model->iam_account_id);

        if (!$iamAccount) {
            $this->setProgress(50, 'IAM account associated with this CRM account not found');
            return;
        }

        $this->setProgress(25, 'Enabling the account');

        // Enable the account
        $iamAccount->update(['is_active' => true]);

        $this->setProgress(75, 'Account has been enabled successfully');

        // Fire the 'enabled' event
        Events::fire('enabled', $iamAccount);

        // Set the process as finished
        $this->setFinished();
    }
}

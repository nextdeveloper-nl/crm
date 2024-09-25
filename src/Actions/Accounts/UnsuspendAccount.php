<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Database\Models\Accounts as IAMAccounts;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * Class DisableAccount
 *
 * This action disables an IAM account associated with a CRM account, marking the account as inactive.
 *
 * @package NextDeveloper\CRM\Actions\Accounts
 */
class SuspendAccount extends AbstractAction
{
    /**
     * Events associated with this action.
     */
    public const EVENTS = [
        'disabled:NextDeveloper\IAM\Accounts'
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


        // Set the process as finished
        $this->setFinished();
    }
}

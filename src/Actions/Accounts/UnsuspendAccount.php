<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\Events\Services\Events;

/**
 * Class UnsuspendAccount
 *
 * This action unsuspends an IAM account associated with a CRM account, marking the account as active.
 */
class UnsuspendAccount extends AbstractAction
{
    /**
     * Events associated with this action.
     */
    public const EVENTS = [
        'unsuspended:NextDeveloper\CRM\Accounts'
    ];

    /**
     * UnsuspendAccount constructor.
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
     * Handle the unsuspend account action.
     *
     * This method updates the account's status to unsuspended and fires an event.
     *
     * @return void
     */
    public function handle(): void
    {
        // Set initial progress
        $this->setProgress(0, 'Starting to disable account');

        // Update the account's status to unsuspended
        $this->model->update(['is_suspended' => false]);

        // Fire an event indicating the account has been unsuspended
        Events::fire('unsuspended', $this->model);

        // Set the process as finished
        $this->setFinished();
    }
}

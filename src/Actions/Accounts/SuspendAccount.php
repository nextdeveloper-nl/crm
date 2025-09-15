<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\Events\Services\Events;

/**
 * This action disables an IAM account associated with a CRM account, marking the account as inactive.
 */
class SuspendAccount extends AbstractAction
{
    /**
     * Events associated with this action.
     */
    public const EVENTS = [
        'suspended:NextDeveloper\CRM\Accounts'
    ];

    /**
     * SuspendAccount constructor.
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
     * Handle the suspend account action.
     *
     * This method updates the account's status to suspended and fires an event.
     *
     * @return void
     */
    public function handle(): void
    {
        // Set initial progress
        $this->setProgress(0, 'Starting to disable account');

        // Update the account's status to suspended
        $this->model->update(['is_suspended' => true]);

        // Fire an event indicating the account has been disabled
        Events::fire('suspended', $this->model);

        // Set the process as finished
        $this->setFinished();
    }
}

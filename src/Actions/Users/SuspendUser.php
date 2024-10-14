<?php

namespace NextDeveloper\CRM\Actions\Users;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\Events\Services\Events;

/**
 * Class SuspendUser
 *
 * This action suspends a user in the CRM system, marking the user as inactive.
 *
 * @package NextDeveloper\CRM\Actions\Accounts
 */
class SuspendUser extends AbstractAction
{
    /**
     * Events associated with this action.
     */
    public const EVENTS = [
        'suspended:NextDeveloper\CRM\Users'
    ];

    /**
     * SuspendUser constructor.
     *
     * Initializes the action with the given CRM users.
     *
     * @param Users $users The user model to be suspended.
     * @throws NotAllowedException If the action is not allowed.
     */
    public function __construct(Users $users)
    {
        $this->model = $users;
        parent::__construct();
    }

    /**
     * Handle the suspend user action.
     *
     * This method updates the user's status to suspended and fires an event.
     *
     * @return void
     */
    public function handle(): void
    {
        // Set initial progress
        $this->setProgress(0, 'Starting to disable user');

        // Update the user's status to suspended
        $this->model->update(['is_suspended' => true]);

        // Fire an event indicating the user has been disabled
        Events::fire('suspended', $this->model);

        // Set the process as finished
        $this->setFinished();
    }
}
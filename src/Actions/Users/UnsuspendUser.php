<?php

namespace NextDeveloper\CRM\Actions\Users;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\Events\Services\Events;

/**
 * Class UnsuspendUser
 *
 * This action unsuspends a user in the CRM system, marking the user as active.
 *
 * @package NextDeveloper\CRM\Actions\Users
 */
class UnsuspendUser extends AbstractAction
{
    /**
     * Events associated with this action.
     */
    public const EVENTS = [
        'unsuspended:NextDeveloper\CRM\Users'
    ];

    /**
     * UnsuspendUser constructor.
     *
     * Initializes the action with the given CRM user.
     *
     * @param Users $users The user model to be unsuspended.
     * @throws NotAllowedException If the action is not allowed.
     */
    public function __construct(Users $users)
    {
        $this->model = $users;
        parent::__construct();
    }

    /**
     * Handle the unsuspend user action.
     *
     * This method updates the user's status to unsuspended and fires an event.
     *
     * @return void
     */
    public function handle(): void
    {
        // Set initial progress
        $this->setProgress(0, 'Starting to unsuspend user');

        // Update the user's status to unsuspended
        $this->model->update(['is_suspended' => false]);

        // Fire an event indicating the user has been unsuspended
        Events::fire('unsuspended', $this->model);

        // Set the process as finished
        $this->setFinished();
    }
}
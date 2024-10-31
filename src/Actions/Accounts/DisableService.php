<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Accounts;

/**
 * Class DisableService
 *
 * This class handles the disabling of a service for an account.
 */
class DisableService extends AbstractAction
{
    /**
     * Events associated with disabling the service.
     *
     * @var array
     */
    public const array EVENTS = [
        'disable-service:NextDeveloper\CRM\Accounts'
    ];

    /**
     * DisableService constructor.
     *
     * @param Accounts $accounts The account model instance.
     * @throws NotAllowedException
     */
    public function __construct(Accounts $accounts)
    {
        $this->model = $accounts;
        parent::__construct();
    }

    /**
     * Handles the disabling of the service.
     *
     * This method updates the account to set the service as disabled.
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Starting to disable service');

        $this->model->updateQuietly([
            'is_service_enabled' => false
        ]);

        $this->setProgress(100, 'Service disabled');
    }
}
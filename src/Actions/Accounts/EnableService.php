<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\CRM\Actions\Concerns\RequiresSalesManager;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Accounts;

/**
 * Class EnableService
 *
 * This class handles the enabling of a service for an account.
 */
class EnableService extends AbstractAction
{
    use RequiresSalesManager;

    /**
     * Events associated with enabling the service.
     *
     * @var array
     */
    public const EVENTS = [
        'enable-service:NextDeveloper\CRM\Accounts'
    ];

    /**
     * EnableService constructor.
     *
     * @param Accounts $accounts The account model instance.
     * @throws NotAllowedException
     */
    public function __construct(Accounts $accounts)
    {
        //  Only a sales manager (or sales admin) may change an account's state, see RequiresSalesManager.
        $this->requireSalesManager();

        $this->model = $accounts;
        parent::__construct();
    }

    /**
     * Handles the enabling of the service.
     *
     * This method updates the account to set the service as enabled.
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Starting to enable service');

        $this->model->updateQuietly([
            'is_service_enabled' => true
        ]);

        $this->setProgress(100, 'Service enabled');
    }
}

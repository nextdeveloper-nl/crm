<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Services\RiskManagement\RiskManagementService;
use NextDeveloper\Events\Services\Events;

/**
 * This action, calculates the risk points for the account by looking at the user behaviour with the objects,
 * and not limited to;
 *
 * - User information
 * - Payment cycle information
 * - Service usage behaviour
 */
class CalculateAccountRisk extends AbstractAction
{
    public const CHECKPOINTS = [
        '0' =>  'Booting the action',
        '10' => 'Calculating risk points based on user information',
        '30' => 'Calculating risk points based on payment cycle information',
        '70' => 'Calculating risk points based on service usage behaviour',
        '90' => 'Finalizing the risk points calculation',
        '100' => 'Risk points calculated',
    ];

    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Accounts $accounts
     */
    public function __construct(Accounts $accounts, $params = null, $previousAction = null)
    {
        $this->model = $accounts;

        $this->queue = 'crm';

        parent::__construct($params, $previousAction);
    }

    public function handle()
    {
        $this->setProgress(0, 'Booting the action');

        if($this->shouldRunCheckpoint(10)) {
            $this->model->update([
                'risk_level'    =>  (new RiskManagementService($this->model))->calculateRiskLevel()
            ]);

            $this->setProgress(10, 'Risk points calculated');
        }

        $this->setFinished('Risk calculated');

        Events::fire('risk-calculated', $this->model);
    }
}

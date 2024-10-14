<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\EventHandlers\Accounts\CrmAccountsUpdatedEvent;
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
    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Accounts $accounts
     */
    public function __construct(Accounts $accounts)
    {
        $this->model = $accounts;
    }

    public function handle()
    {
        $this->model->update([
            'risk_level'    =>  (new RiskManagementService($this->model))->calculateRiskLevel()
        ]);

        Events::fire('risk-calculated', $this->model);
    }
}

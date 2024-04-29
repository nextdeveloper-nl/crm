<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\EventHandlers\Accounts\CrmAccountsUpdatedEvent;
use NextDeveloper\CRM\Services\RiskManagement\RiskManagementService;

/**
 * This action, calculates the risk points for the account by looking at the user behaviour with the objects,
 * and not limited to;
 *
 * - User information
 * - Payment cycle information
 * - Service usage behaviour
 */
class CalculateAccountRisk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $account = null;

    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Accounts $accounts
     */
    public function __construct(Accounts $accounts)
    {
        $this->account = $accounts;
    }

    public function handle()
    {
        $this->account->update([
            'risk_level'    =>  (new RiskManagementService($this->account))->calculateRiskLevel()
        ]);

        event(new CrmAccountsUpdatedEvent($this->account));
    }
}

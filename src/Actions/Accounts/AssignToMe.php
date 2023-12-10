<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\EventHandlers\Accounts\CrmAccountsUpdatedEvent;

class AssignToMe implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $account = null;

    /**
     * This function adds the current user as Account Manager for this account.
     *
     * @param Accounts $accounts
     */
    public function __construct(Accounts $accounts)
    {
        $this->account = $accounts;
    }

    public function handle()
    {
        /**
         * Here we will add the user to this
         */

        event(new CrmAccountsUpdatedEvent($this->account));
    }
}

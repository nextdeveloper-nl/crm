<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\CRM\Database\Models\Accounts;

class WatchAccount extends AbstractAction
{
    public const EVENTS = [
        'watching:NextDeveloper\CRM\Accounts'
    ];

    /**
     * This function adds the current user as Account Manager for this account.
     *
     * @param Accounts $accounts
     */
    public function __construct(Accounts $accounts)
    {
        $this->model = $accounts;

        parent::__construct(null);
    }

    public function handle()
    {
        /**
         * Here we need to add a record to crm_account_managers for current user. And send an email as watching.
         */

        //Events::fire('watching', );
    }
}

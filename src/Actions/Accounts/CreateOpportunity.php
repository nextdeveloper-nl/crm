<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\CRM\Database\Models\Accounts;


/**
 * This action creates an opportunity for the related account
 */
class CreateOpportunity extends AbstractAction
{
    public const EVENTS = [
        'created:NextDeveloper\CRM\Opportunities'
    ];

    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Accounts $users
     */
    public function __construct(Accounts $accounts)
    {
        $this->model = $accounts;

        parent::__construct();
    }

    public function handle()
    {
        /**
         *  Here we will create an opportunity with name "Initial opportunity"
         */
    }
}

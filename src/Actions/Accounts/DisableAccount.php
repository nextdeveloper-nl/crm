<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\Events\Services\Events;

/**
 * This job resets the users password and send a security link with a token in email
 * so that use can come to the web site and/or control panel or authentication service,
 * to reset users password
 */
class DisableAccount extends AbstractAction
{
    public const EVENTS = [
        'disabled:NextDeveloper\IAM\Accounts'
    ];

    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Users $users
     */
    public function __construct(Accounts $accounts)
    {
        $this->model = $accounts;

        parent::__construct();
    }

    public function handle()
    {
        /**
         * Disable the account in iam_accounts table and create the event as account_disabled
         */

        //Events::fire('account_disabled', ...);
    }
}

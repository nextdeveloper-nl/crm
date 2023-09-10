<?php

namespace NextDeveloper\CRM\EventHandlers\Accounts;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class CrmAccountsDeletedEvent
 * @package PlusClouds\Account\Handlers\Events
 */
class CrmAccountsDeletedEvent implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle($event)
    {

    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
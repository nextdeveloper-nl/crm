<?php

namespace NextDeveloper\CRM\Actions\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Accounts;

/**
 * This action tries to understand if there is an opportunity for this account
 */
class CreateOpportunity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Accounts $users
     */
    public function __construct(Accounts $accounts)
    {

    }

    public function handle()
    {
        $this->createInitialOpportunity();
    }

    private function createInitialOpportunity() : void {

    }
}

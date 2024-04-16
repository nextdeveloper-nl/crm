<?php

namespace NextDeveloper\CRM\Actions\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Accounts;

/**
 * This action creates an opportunity for the related account
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
        /*
         * While creating the opportunity you should use the iam_user_id from UserHelper::()->id;
         */
    }

    public function handle()
    {
        $isInitial = $this->createInitialOpportunity();

        if(!$isInitial) {

        }
    }

    /**
     * Checks if the account has the initial opportunity or not. If it does not have the initial opportunity then
     * it creates the first opportunity for this account.
     * @return bool
     */
    private function createInitialOpportunity() : bool {

    }

    /**
     * This function creates a generic opportunity and let the Account Manager deal with this opportunity.
     * @return bool
     */
    private function createGenericOpportunity() : bool {

    }
}

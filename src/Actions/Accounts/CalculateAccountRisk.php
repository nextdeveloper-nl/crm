<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Users;

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

    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Users $users
     */
    public function __construct(Users $users)
    {

    }

    public function handle()
    {

    }
}

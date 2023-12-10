<?php

namespace NextDeveloper\CRM\Actions\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\IAM\Database\Models\Accounts;

/**
 * This action takes a User and moves it to another account.
 *
 * WARNING: This action can only run if the user is not registered.
 */
class MigrateToAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * This action will take the user and make a relation under a given account.
     *
     * @param Users $users
     * @param Accounts $accounts
     */
    public function __construct(Users $users, Accounts $accounts)
    {

    }

    public function handle()
    {

    }
}

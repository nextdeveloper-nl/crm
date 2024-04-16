<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Users;

/**
 * This job resets the users password and send a security link with a token in email
 * so that use can come to the web site and/or control panel or authentication service,
 * to reset users password
 */
class DisableAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Sample action;
     * https://.../iam/users/{user-id}/action/reset-password
     */

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

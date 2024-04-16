<?php

namespace NextDeveloper\CRM\EventHandlers\UsersCreatedEvent;

use App\Mail\CRM\WelcomeEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

/**
 * Class UsersCreatedEvent
 *
 * @package PlusClouds\Account\Handlers\Events
 */
class UsersCreatedEvent implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle($event)
    {
        Mail::to('baris.bulut@plusclouds.com')
            ->send(new WelcomeEmail($event->model));
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}

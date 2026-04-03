<?php

namespace App\Listeners\Admin;

use App\Events\Admin\UserStatusChanged;
use App\Jobs\Admin\SendUserStatusEmailNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserStatusEmailNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserStatusChanged $event): void
    {
        SendUserStatusEmailNotificationJob::dispatch($event->userId);
    }
}

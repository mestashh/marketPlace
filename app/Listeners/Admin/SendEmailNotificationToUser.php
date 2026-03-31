<?php

namespace App\Listeners\Admin;

use App\Events\Admin\AdminJoinedConversation;
use App\Jobs\Admin\SendEmailNotificationToUserJob;

class SendEmailNotificationToUser
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
    public function handle(AdminJoinedConversation $event): void
    {
        SendEmailNotificationToUserJob::dispatch($event->conversationId);
    }
}

<?php

namespace App\Listeners\Admin;

use App\Events\Admin\AdminJoinedConversation;
use App\Jobs\Admin\AdminJoinedConversationNotificationToUserJob;

class AdminJoinedConversationNotificationToUser
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
        AdminJoinedConversationNotificationToUserJob::dispatch($event->conversationId);
    }
}

<?php

namespace App\Listeners\Admin;

use App\Events\Admin\AdminJoinedConversation;
use App\Jobs\Admin\AdminJoinedConversationNotificationToSellerJob;

class AdminJoinedConversationNotificationToSeller
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
        AdminJoinedConversationNotificationToSellerJob::dispatch($event->conversationId);
    }
}

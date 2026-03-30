<?php

namespace App\Listeners\Conversation;

use App\Events\Conversation\ConversationCreated;
use App\Jobs\Conversation\SendEmailNotificationToSellerJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailNotificationToSeller
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
    public function handle(ConversationCreated $event): void
    {
        SendEmailNotificationToSellerJob::dispatch($event->conversationId);
    }
}

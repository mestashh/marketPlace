<?php

namespace App\Listeners\Conversation;

use App\Events\Conversation\ConversationCreated;
use App\Jobs\Conversation\SendEmailNotificationToUserJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
    public function handle(ConversationCreated $event): void
    {
        SendEmailNotificationToUserJob::dispatch($event->conversationId);
    }
}

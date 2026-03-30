<?php

namespace App\Listeners\Admin;

use App\Events\Conversation\AdminCalled;
use App\Jobs\Admin\NotifyAdminAboutConversationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminAboutConversation
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
    public function handle(AdminCalled $event): void
    {
        NotifyAdminAboutConversationJob::dispatch($event->conversationId);
    }
}

<?php

namespace App\Jobs\Admin;

use App\Mail\Admin\AdminJoinedConversationNotificationToSellerMail;
use App\Models\Conversation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class AdminJoinedConversationNotificationToSellerJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $conversationId)
    {
        $this->afterCommit();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $conversation = Conversation::find($this->conversationId);
        Mail::to($conversation->user->email)->send(new AdminJoinedConversationNotificationToSellerMail($conversation));
    }
}

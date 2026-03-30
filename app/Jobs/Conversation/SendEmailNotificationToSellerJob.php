<?php

namespace App\Jobs\Conversation;

use App\Mail\Conversation\NotificationToSellerMail;
use App\Models\Conversation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEmailNotificationToSellerJob implements ShouldQueue
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
        Mail::to($conversation->seller->user->email)->send(new NotificationToSellerMail($conversation));
    }
}

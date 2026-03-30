<?php

namespace App\Jobs\Admin;

use App\Models\User;
use App\Notifications\Admin\AdminCalledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class NotifyAdminAboutConversationJob implements ShouldQueue
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
        $admins = User::whereHas('admin')->get();
        Notification::send($admins, new AdminCalledNotification($this->conversationId));
    }
}

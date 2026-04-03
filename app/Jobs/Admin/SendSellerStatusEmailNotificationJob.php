<?php

namespace App\Jobs\Admin;

use App\Mail\Admin\SellerStatusNotificationMail;
use App\Models\Seller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendSellerStatusEmailNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $sellerId)
    {
        $this->afterCommit();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $seller = Seller::findOrFail($this->sellerId);
        Mail::to($seller->user->email)->send(new SellerStatusNotificationMail($seller));
    }
}

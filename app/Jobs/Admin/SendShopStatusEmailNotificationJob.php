<?php

namespace App\Jobs\Admin;

use App\Mail\Admin\ShopStatusNotificationMail;
use App\Models\Shop;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendShopStatusEmailNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $shopId)
    {
        $this->afterCommit();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $shop = Shop::findOrFail($this->shopId);
        Mail::to($shop->seller->user->email)->send(new ShopStatusNotificationMail($shop));
    }
}

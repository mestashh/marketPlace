<?php

namespace App\Jobs\Admin;

use App\Mail\Admin\ProductStatusNotificationMail;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendProductStatusEmailNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $productId)
    {
        $this->afterCommit();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $product = Product::findOrFail($this->productId);
        Mail::to($product->shop->seller->user->email)->send(new ProductStatusNotificationMail($product));
    }
}

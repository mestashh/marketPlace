<?php

namespace App\Listeners\Admin;

use App\Events\Admin\ProductStatusChanged;
use App\Jobs\Admin\SendProductStatusEmailNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendProductStatusEmailNotification
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
    public function handle(ProductStatusChanged $event): void
    {
        SendProductStatusEmailNotificationJob::dispatch($event->productId);
    }
}

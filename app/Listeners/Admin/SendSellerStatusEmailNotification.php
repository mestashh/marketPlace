<?php

namespace App\Listeners\Admin;

use App\Events\Admin\SellerStatusChanged;
use App\Jobs\Admin\SendSellerStatusEmailNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSellerStatusEmailNotification
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
    public function handle(SellerStatusChanged $event): void
    {
        SendSellerStatusEmailNotificationJob::dispatch($event->sellerId);
    }
}

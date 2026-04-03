<?php

namespace App\Listeners\Admin;

use App\Events\Admin\ShopStatusChanged;
use App\Jobs\Admin\SendShopStatusEmailNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendShopStatusEmailNotification
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
    public function handle(ShopStatusChanged $event): void
    {
        SendShopStatusEmailNotificationJob::dispatch($event->shopId);
    }
}

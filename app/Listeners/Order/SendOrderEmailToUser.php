<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCreated;
use App\Jobs\Order\SendOrderEmailToUserJob;

class SendOrderEmailToUser
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
    public function handle(OrderCreated $event): void
    {
        $order = $event->orderId;
        SendOrderEmailToUserJob::dispatch($order);
    }
}

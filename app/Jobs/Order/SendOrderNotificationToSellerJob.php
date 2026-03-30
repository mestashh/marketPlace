<?php

namespace App\Jobs\Order;

use App\Mail\Order\OrderNotificationToSellerMail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOrderNotificationToSellerJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $orderId)
    {
        $this->afterCommit();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::with('shopOrders.shop.seller.user')->find($this->orderId);
        foreach ($order->shopOrders as $shopOrder) {
            $seller = $shopOrder->shop->seller->user;
            Mail::to($seller->email)->send(new OrderNotificationToSellerMail($shopOrder));
        }

    }
}

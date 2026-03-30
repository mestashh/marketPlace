<?php

namespace App\Jobs\Order;

use App\Mail\Order\OrderNotificationToUserMail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOrderEmailToUserJob implements ShouldQueue
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
        $order = Order::where('id', $this->orderId)->first();
        $user = $order->user;
        Mail::to($user->email)->send(new OrderNotificationToUserMail);
    }
}

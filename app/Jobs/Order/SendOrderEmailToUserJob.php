<?php

namespace App\Jobs\Order;

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
    public function __construct(public Order $order)
    {
        $this->afterCommit();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::raw('Order', function ($message) {
            $message->to($this->order->user->email)
                ->subject('Order '.$this->order->id.' was created, more info: '.$this->order);
        });
    }
}

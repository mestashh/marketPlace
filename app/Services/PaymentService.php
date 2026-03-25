<?php

namespace App\Services;

use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

class PaymentService
{
    public function store(User $user, array $data, Order $order)
    {
        return Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'payment_method_id' => $data['payment_method_id'],
            'status' => PaymentStatusEnum::PENDING->value,
            'amount' => $order->total_price,
        ]);
    }
}

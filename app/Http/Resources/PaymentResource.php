<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $uuid
 * @property mixed $order
 * @property mixed $amount
 * @property mixed $status
 * @property mixed $paymentMethod
 */
class PaymentResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<int, string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'order_uuid' => $this->order->uuid,
            'payment_method' => $this->paymentMethod->payment_method,
            'status' => $this->status,
            'amount' => $this->amount,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    private string $id;
    private string $order_id;
    private string $payment_method_id;
    private string $status;
    private string $amount;

    /**
     * Transform the resource into an array.
     *
     * @return array<int, string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'payment_method_id' => $this->payment_method_id,
            'status' => $this->status,
            'amount' => $this->amount,
        ];
    }
}

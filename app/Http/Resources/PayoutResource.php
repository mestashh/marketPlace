<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayoutResource extends JsonResource
{
    private string $id;
    private string $shop_order_id;
    private string $seller_payout_method_id;
    private string $status;
    private string $amount;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shop_order_id' => $this->shop_order_id,
            'seller_payout_method_id' => $this->seller_payout_method_id,
            'status' => $this->status,
            'amount' => $this->amount,
        ];
    }
}

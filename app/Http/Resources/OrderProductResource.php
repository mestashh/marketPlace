<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    private string $id;
    private string $order_id;
    private string $product_id;
    private string $price_at_purchase;
    private string $product_price;
    private string $user_id;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'price_at_purchase' => $this->price_at_purchase,
            'product_price' => $this->product_price,
            'user_id' => $this->user_id,
        ];
    }
}

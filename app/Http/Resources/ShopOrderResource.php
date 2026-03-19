<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $subtotal_price
 * @property mixed $shop
 * @property mixed $orderItems
 */
class ShopOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'shop' => $this->shop->name,
            'shop_uuid' => $this->shop->uuid,
            'subtotal_price' => $this->subtotal_price,
            'items' => OrderItemResource::collection($this->orderItems),
        ];
    }
}

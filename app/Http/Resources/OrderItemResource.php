<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $productVariant
 * @property mixed $quantity
 * @property mixed $price_at_purchase
 */
class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_variant_uuid' => $this->productVariant->uuid,
            'quantity' => $this->quantity,
            'price_at_purchase' => $this->price_at_purchase,
        ];
    }
}

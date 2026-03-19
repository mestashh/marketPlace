<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $uuid
 * @property mixed $quantity
 * @property mixed $price
 * @property mixed $productVariant
 */
class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'product_variant_uuid' => $this->productVariant->uuid,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'images' => ProductImageResource::collection($this->productVariant->product->productImages),
        ];
    }
}

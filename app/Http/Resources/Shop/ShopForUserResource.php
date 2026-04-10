<?php

namespace App\Http\Resources\Shop;

use App\Http\Resources\Product\ProductForUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $seller_id
 * @property mixed $name
 * @property mixed $description
 * @property mixed $status
 * @property mixed $uuid
 * @property mixed $seller
 * @property mixed $products
 */
class ShopForUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'seller_uuid' => $this->seller->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'products' => ProductForUserResource::collection($this->products),
        ];
    }
}

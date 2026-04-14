<?php

namespace App\Http\Resources\ProductVariant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $uuid
 * @property mixed $access_status
 * @property mixed $name
 * @property mixed $description
 * @property mixed $price
 * @property mixed $stock
 * @property mixed $sku
 */
class ProductVariantForAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_variant_uuid' => $this->uuid,
            'access_status' => $this->access_status,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'sku' => $this->sku,
        ];
    }
}

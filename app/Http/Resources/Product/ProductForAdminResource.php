<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\ProductImageResource;
use App\Http\Resources\ProductVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $uuid
 * @property mixed $shop
 * @property mixed $category
 * @property mixed $productImages
 * @property mixed $productVariants
 * @property mixed $description
 * @property mixed $name
 * @property mixed $access_status
 */
class ProductForAdminResource extends JsonResource
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
            'shop_uuid' => $this->shop->uuid,
            'category_uuid' => $this->category->uuid,
            'name' => $this->name,
            'access_status' => $this->access_status,
            'description' => $this->description,
            'product_variants' => ProductVariantResource::collection($this->productVariants),
            'product_images' => ProductImageResource::collection($this->productImages),
        ];
    }
}

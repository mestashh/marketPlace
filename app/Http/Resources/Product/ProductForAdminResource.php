<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\ProductImage\ProductImageForUserResource;
use App\Http\Resources\ProductVariant\ProductVariantForUserResource;
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
            'product_variants' => ProductVariantForUserResource::collection($this->productVariants),
            'product_images' => ProductImageForUserResource::collection($this->productImages),
        ];
    }
}

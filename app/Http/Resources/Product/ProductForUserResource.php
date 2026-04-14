<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\ProductImage\ProductImageForUserResource;
use App\Http\Resources\ProductVariant\ProductVariantForUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $shop
 * @property mixed $uuid
 * @property mixed $name
 * @property mixed $description
 * @property mixed $status
 * @property mixed $category
 * @property mixed $productVariants
 * @property mixed $productImages
 */
class ProductForUserResource extends JsonResource
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
            'description' => $this->description,
            'product_variants' => ProductVariantForUserResource::collection($this->productVariants),
            'product_images' => ProductImageForUserResource::collection($this->productImages),
        ];
    }
}

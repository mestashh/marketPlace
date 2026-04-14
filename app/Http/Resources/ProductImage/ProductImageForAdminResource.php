<?php

namespace App\Http\Resources\ProductImage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $path
 * @property mixed $product
 * @property mixed $uuid
 * @property mixed $is_main
 * @property mixed $position
 * @property mixed $access_status
 */
class ProductImageForAdminResource extends JsonResource
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
            'status' => $this->access_status,
            'product_uuid' => $this?->product?->uuid,
            'path' => $this->path,
            'is_main' => $this->is_main,
            'position' => $this->position,
        ];
    }
}

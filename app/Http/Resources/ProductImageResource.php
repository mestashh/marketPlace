<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $path
 * @property mixed $product
 * @property mixed $uuid
 * @property mixed $is_main
 * @property mixed $position
 */
class ProductImageResource extends JsonResource
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
            'product_uuid' => $this->product->uuid,
            'path' => $this->path,
            'is_main' => $this->is_main,
            'position' => $this->position,
        ];
    }
}

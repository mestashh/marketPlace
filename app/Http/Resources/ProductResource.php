<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $shop
 * @property mixed $uuid
 * @property mixed $name
 * @property mixed $description
 * @property mixed $status
 * @property mixed $category
 */
class ProductResource extends JsonResource
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
        ];
    }
}

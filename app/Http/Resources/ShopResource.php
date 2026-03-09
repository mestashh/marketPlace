<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $seller_id
 * @property mixed $name
 * @property mixed $description
 * @property mixed $status
 * @property mixed $uuid
 */
class ShopResource extends JsonResource
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
        ];
    }
}

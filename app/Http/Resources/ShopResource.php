<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $seller_id
 * @property mixed $name
 * @property mixed $description
 * @property mixed $status
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
            'seller_id' => $this->seller_id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }
}

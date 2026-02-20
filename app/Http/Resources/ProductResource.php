<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    private string $id;
    private string $shop_id;
    private string $category_id;
    private string $name;
    private string $description;
    private string $status;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }
}

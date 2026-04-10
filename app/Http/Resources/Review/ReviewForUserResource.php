<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $user
 * @property mixed $product
 * @property mixed $rating
 * @property mixed $text
 * @property mixed $uuid
 */
class ReviewForUserResource extends JsonResource
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
            'user_uuid' => $this->user->uuid,
            'product_uuid' => $this->product->uuid,
            'rating' => $this->rating,
            'text' => $this->text,
        ];
    }
}

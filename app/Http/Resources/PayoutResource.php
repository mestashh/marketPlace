<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $uuid
 * @property mixed $seller
 * @property mixed $status
 * @property mixed $amount
 */
class PayoutResource extends JsonResource
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
            'seller_uuid' => $this->seller?->uuid,
            'status' => $this->status,
            'amount' => $this->amount,
        ];
    }
}

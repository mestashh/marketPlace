<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $status
 * @property mixed $seller
 * @property mixed $details
 * @property mixed $payoutMethod
 * @property mixed $uuid
 */
class SellerPayoutMethodResource extends JsonResource
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
            'payout_uuid' => $this->uuid,
            'payout_method' => $this->payoutMethod->payout_method,
            'status' => $this->status,
            'details' => $this->details,
        ];
    }
}

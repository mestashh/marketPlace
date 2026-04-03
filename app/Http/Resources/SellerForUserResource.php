<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $balance
 * @property mixed $withdrawable_balance
 * @property mixed $TIN
 * @property mixed $uuid
 */
class SellerForUserResource extends JsonResource
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
            'balance' => $this->balance,
            'withdrawable_balance' => $this->withdrawable_balance,
            'TIN' => $this->TIN,
        ];
    }
}

<?php

namespace App\Http\Resources\Seller;

use App\Http\Resources\Shop\ShopForUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $balance
 * @property mixed $withdrawable_balance
 * @property mixed $TIN
 * @property mixed $uuid
 * @property mixed $shop
 * @property mixed $user
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
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'shop' => new ShopForUserResource($this->shop),
        ];
    }
}

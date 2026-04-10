<?php

namespace App\Http\Resources\Seller;

use App\Http\Resources\Shop\ShopForUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $balance
 * @property mixed $withdrawable_balance
 * @property mixed $TIN
 * @property mixed $access_status
 * @property mixed $shop
 */
class SellerForAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => $this->balance,
            'withdrawable_balance' => $this->withdrawable_balance,
            'TIN' => $this->TIN,
            'access_status' => $this->access_status,
            'shop' => new ShopForUserResource($this->shop),
        ];
    }
}

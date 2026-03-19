<?php

namespace App\Http\Resources;

use App\Models\ShopOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $uuid
 * @property mixed $address
 * @property mixed $status
 * @property mixed $total_price
 * @property mixed $shopOrders
 */
class OrderResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $address = $this->address->country.$this->address->city.$this->address->street.$this->address->house;

        return [
            'uuid' => $this->uuid,
            'address' => $address,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'shop_order' => ShopOrderResource::collection($this->shopOrders),
        ];
    }
}

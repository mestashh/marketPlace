<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $uuid
 * @property mixed $shopOrder
 * @property mixed $user
 * @property mixed $seller
 * @property mixed $admin
 * @property mixed $status
 */
class ConversationResource extends JsonResource
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
            'order_uuid' => $this->shopOrder->order->uuid,
            'shop_order_id' => $this->shopOrder->id,
            'user_uuid' => $this->user->id,
            'seller_uuid' => $this->seller->uuid,
            'admin_uuid' => $this->admin?->uuid,
            'status' => $this->status,
        ];
    }
}

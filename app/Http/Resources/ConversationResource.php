<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    private string $id;
    private string $shop_order_id;
    private string $user_id;
    private string $seller_id;
    private string $admin_id;
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
            'shop_order_id' => $this->shop_order_id,
            'user_id' => $this->user_id,
            'seller_id' => $this->seller_id,
            'admin_id' => $this->admin_id,
            'status' => $this->status,
        ];
    }
}

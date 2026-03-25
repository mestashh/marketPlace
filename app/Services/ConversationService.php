<?php

namespace App\Services;

use App\Enums\ConversationStatusEnum;
use App\Models\Conversation;
use App\Models\ShopOrder;
use App\Models\User;

class ConversationService
{
    public function index(User $user)
    {
        if ($user->isAdmin()) {
            return Conversation::query()->paginate(20);
        } elseif ($user->isSeller()) {
            return Conversation::where('seller_id', $user->seller->id)->get();
        } else {
            return Conversation::where('user_id', $user->id)->get();
        }
    }

    public function store(User $user, array $data)
    {
        $shopOrder = ShopOrder::findOrFail($data['shop_order_id']);

        return Conversation::create([
            'shop_order_id' => $data['shop_order_id'],
            'user_id' => $user->id,
            'seller_id' => $shopOrder->shop->seller->id,
            'status' => ConversationStatusEnum::OPEN->value,
        ]);
    }

    public function callAdmin(Conversation $conversation)
    {
        return $conversation->update([
            'status' => ConversationStatusEnum::CLOSED->value,
        ]);
    }
}

<?php

namespace App\Services;

use App\Enums\ConversationStatusEnum;
use App\Events\Conversation\AdminCalled;
use App\Events\Conversation\ConversationCreated;
use App\Exceptions\Conversation\ConversationCallAdminException;
use App\Exceptions\Conversation\ConversationExistsException;
use App\Models\Conversation;
use App\Models\ShopOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class ConversationService
{
    public function index(User $user)
    {
        if ($user->isAdmin()) {
            return Conversation::paginate(20);
        } elseif ($user->isSeller()) {
            return Conversation::where('seller_id', $user->seller->id)->paginate(20);
        } else {
            return Conversation::where('user_id', $user->id)->paginate(20);
        }
    }

    /**
     * @throws Throwable
     * @throws ConversationExistsException
     */
    public function store(User $user, array $data)
    {
        $shopOrder = ShopOrder::findOrFail($data['shop_order_id']);

        if ($shopOrder->conversations()
            ->where('user_id', $user->id)
            ->exists()) {
            throw new ConversationExistsException;
        }

        return DB::transaction(function () use ($user, $data, $shopOrder) {
            $conversation = Conversation::create([
                'shop_order_id' => $data['shop_order_id'],
                'user_id' => $user->id,
                'seller_id' => $shopOrder->shop->seller->id,
                'status' => ConversationStatusEnum::OPEN->value,
            ]);
            event(new ConversationCreated($conversation->id));

            return $conversation;
        });
    }

    /**
     * @throws ConversationCallAdminException
     */
    public function callAdmin(Conversation $conversation): bool
    {
        if ($conversation->status === ConversationStatusEnum::WAIT_FOR_ADMIN->value) {
            throw new ConversationCallAdminException;
        }

        $updated = $conversation->update([
            'status' => ConversationStatusEnum::WAIT_FOR_ADMIN->value,
        ]);

        event(new AdminCalled($conversation->id));

        return $updated;
    }
}

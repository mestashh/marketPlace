<?php

namespace App\Services;

use App\Enums\ConversationStatusEnum;
use App\Events\Admin\AdminJoinedConversation;
use App\Events\Admin\ProductStatusChanged;
use App\Events\Admin\SellerStatusChanged;
use App\Events\Admin\ShopStatusChanged;
use App\Events\Admin\UserStatusChanged;
use App\Exceptions\Admin\AdminExistException;
use App\Exceptions\Conversation\ConversationAdminException;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\Admin\AdminCalledNotification;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminService
{
    /**
     * @throws AdminExistException
     */
    public function store(array $data)
    {
        if (Admin::where('user_id', $data['user_id'])
            ->exists()) {
            throw new AdminExistException;
        }

        return Admin::create([
            'user_id' => $data['user_id'],
            'role' => $data['role'],
        ]);
    }

    /**
     * @throws ConversationAdminException
     * @throws Throwable
     */
    public function joinConversation(User $user, int $conversationId): \Illuminate\Http\JsonResponse
    {
        $conversation = Conversation::findOrFail($conversationId);
        if ($conversation->status !== ConversationStatusEnum::WAIT_FOR_ADMIN->value
            || $conversation->admin_id !== null) {
            throw new ConversationAdminException;
        }
        DB::transaction(function () use ($user, $conversationId, $conversation) {
            $conversation->update([
                'admin_id' => $user->admin->id,
                'status' => ConversationStatusEnum::OPEN->value,
            ]);

            DB::table('notifications')
                ->where('type', AdminCalledNotification::class)
                ->where('data->conversation_id', $conversationId)
                ->delete();
            event(new AdminJoinedConversation($conversationId));

        });

        return response()->json(['message' => 'Join conversation success'], 200);
    }

    public function changeStatus(array $data): void
    {
        $map = [
            'user' => [User::class, UserStatusChanged::class],
            'seller' => [Seller::class, SellerStatusChanged::class],
            'product' => [Product::class, ProductStatusChanged::class],
            'shop' => [Shop::class, ShopStatusChanged::class],
        ];
        [$modelClass, $eventClass] = $map[$data['type']];
        $item = $modelClass::findOrFail($data['id']);
        $item->update([
            'access_status' => $data['access_status'],
        ]);
        event(new $eventClass($item->id));
    }
}

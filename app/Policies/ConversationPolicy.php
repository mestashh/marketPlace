<?php

namespace App\Policies;

use App\Enums\ConversationStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isSeller() || $user->hasOrders();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $user->isAdmin() || $user->id == $conversation->user_id || ($user->isSeller() && $user->seller->id == $conversation->seller_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Order $order): bool
    {
        return $user->hasOrders() && $order->status == OrderStatusEnum::DELIVERED->value && $user->id == $order->user_id;
    }

    /**
     * Call admin
     * @param User $user
     * @param Conversation $conversation
     * @return bool
     */
    public function callAdmin(User $user, Conversation $conversation): bool
    {
        return $user->id == $conversation->user_id && $conversation->admin_id == null && $conversation->status == ConversationStatusEnum::OPEN->value;
    }

    /**
     * Close the conversation
     * @param User $user
     * @param Conversation $conversation
     * @return bool
     */
    public function close(User $user, Conversation $conversation): bool
    {
        return $user->id == $conversation->user_id && $conversation->status == ConversationStatusEnum::OPEN->value;
    }
}

<?php

namespace App\Policies;

use App\Enums\OrderStatusEnum;
use App\Enums\StatusEnum;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id == $order->user_id && $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return ! $user->cart->cartItems->isEmpty()
            && $user->hasVerifiedEmail()
            && $user->access_status == StatusEnum::ACCESS->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->id == $order->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return $order->status === OrderStatusEnum::PAID->value || $order->status === OrderStatusEnum::CREATED->value;
    }
}

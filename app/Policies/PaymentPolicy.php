<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return ($user->isAdmin() && $user->admin->role == AdminRoleEnum::SUPER_ADMIN->value) || $user->hasOrders();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->id == $payment->order->user->id || ($user->isAdmin() && $user->admin->role == AdminRoleEnum::SUPER_ADMIN->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Order $order): bool
    {
        return $user->hasOrders() && $user->id == $order->user_id && $order->status == OrderStatusEnum::CREATED->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): bool
    {
        return false;
    }
}

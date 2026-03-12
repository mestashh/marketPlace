<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Models\PaymentMethod;
use App\Models\User;

class PaymentMethodPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, PaymentMethod $paymentMethod): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
        // return $user->isAdmin() && $user->admin->role === AdminRoleEnum::SUPER_ADMIN->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentMethod $paymentMethod): bool
    {
        return false;
        // return $user->isAdmin() && $user->admin->role === AdminRoleEnum::SUPER_ADMIN->value;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentMethod $paymentMethod): bool
    {
        return false;
        // return $user->isAdmin() && $user->admin->role === AdminRoleEnum::SUPER_ADMIN->value;
    }
}

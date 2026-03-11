<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Models\Seller;
use App\Models\User;

class SellerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Seller $seller): bool
    {
        return $user->isAdmin() || $user->id === $seller->user_id;
    }

    public function create(User $user): bool
    {
        return ! $user->isSeller();
    }

    public function delete(User $user, Seller $seller): bool
    {
        return $user->isAdmin() || $seller->user_id === $user->id;
    }

    public function update(User $user, Seller $seller): bool
    {
        return $user->isAdmin() || $user->id === $seller->user_id;
    }

    public function changeStatus(User $user): bool
    {
        return $user->isAdmin() && $user->admin->role === AdminRoleEnum::SUPER_ADMIN->value;
    }
}

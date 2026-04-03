<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;

class SellerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Seller $seller): bool
    {
        return true;
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

    public function showSellerInfo(User $user, Seller $seller): bool
    {
        return $user->id === $seller->user->id || $user->isAdmin();
    }
}

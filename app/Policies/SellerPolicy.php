<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;

class SellerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->isAdmin();
    }

    public function view(User $authUser, Seller $seller): bool
    {
        return $authUser->isAdmin() || $authUser->id === $seller->user_id;
    }

    public function create(User $authUser): bool
    {
        return true;
    }

    public function delete(User $authUser, Seller $seller): bool
    {
        return $authUser->isAdmin() || $seller->user_id === $authUser->id;
    }

    public function update(User $authUser, Seller $seller): bool
    {
        return $authUser->isAdmin() || $authUser->id === $seller->user_id;
    }
}

<?php

namespace App\Policies;

use App\Enums\StatusEnum;
use App\Models\Seller;
use App\Models\User;

class SellerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Seller $seller): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return (! $user->isSeller()) && $user->access_status === StatusEnum::ACCESS->value;
    }

    public function update(User $user, Seller $seller): bool
    {
        return $user->isAdmin() || ($user->id === $seller->user_id && $seller->access_status == StatusEnum::ACCESS->value);
    }

    public function showSellerInfo(User $user, Seller $seller): bool
    {
        return $user->id === $seller->user->id || $user->isAdmin();
    }
}

<?php

namespace App\Policies;

use App\Enums\UserStatusEnum;
use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(User $user, Shop $shop): bool
    {
        return $user->isAdmin() || $user->id === $shop->seller->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isSeller() && $user->seller->access_status === UserStatusEnum::ACCESS->value;
    }

    public function update(User $user, Shop $shop): bool
    {
        return $shop->seller_id === $user->seller->id;
    }

    public function delete(User $user, Shop $shop): bool
    {
        return $user->isAdmin() || $user->id == $shop->seller->user_id;
    }
}

<?php

namespace App\Policies;

use App\Enums\StatusEnum;
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

    public function view(?User $user, Shop $shop): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isSeller() && $user->seller->access_status === StatusEnum::ACCESS->value && ! $user->seller->hasShop();
    }

    public function update(User $user, Shop $shop): bool
    {
        return $user->isSeller() && $shop->seller_id === $user->seller->id || $user->isAdmin();
    }
}

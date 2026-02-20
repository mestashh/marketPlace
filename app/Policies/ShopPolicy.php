<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(): bool
    {
        return true;
    }

    public function view(User $authUser, Shop $shop): bool
    {
        return $authUser->isAdmin() || $authUser->id === $shop->seller->user_id;
    }
}

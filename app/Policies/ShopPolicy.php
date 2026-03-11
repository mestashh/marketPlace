<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
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

    public function view(User $user, Shop $shop): bool
    {
        return $user->isAdmin() || $user->id === $shop->seller->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isSeller() && $user->seller->access_status === StatusEnum::ACCESS->value;
    }

    public function update(User $user, Shop $shop): bool
    {
        return $shop->seller_id === $user->seller->id;
    }

    public function delete(User $user, Shop $shop): bool
    {
        return $user->isAdmin() || $user->id == $shop->seller->user_id;
    }
    public function changeStatus(User $user): bool
    {
        return $user->isAdmin() && $user->admin->role === AdminRoleEnum::SUPER_ADMIN->value;
    }
}

<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Enums\StatusEnum;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
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
    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSeller() && $user->seller->hasShop() && $user->seller->access_status === StatusEnum::ACCESS->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->isSeller() &&
            $user->seller->hasShop() &&
            $user->seller->shop->id == $product->shop_id &&
            $user->seller->access_status === StatusEnum::ACCESS->value ||
            ($user->isAdmin() && $user->admin->role === AdminRoleEnum::SUPER_ADMIN->value);
    }
}

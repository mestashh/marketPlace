<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Enums\StatusEnum;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;

class ProductVariantPolicy
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
    public function view(?User $user, ProductVariant $productVariant): bool
    {
        if ($productVariant->access_status === StatusEnum::ACCESS->value) {
            return true;
        }

        return $user && ($user->isAdmin() ||
            $user->isSeller() && $user->seller->hasShop() &&
            $user->seller->id === $productVariant->product->shop->seller_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Product $product): bool
    {
        return $user->seller?->shop?->id === $product->shop_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductVariant $productVariant): bool
    {
        return $user->isSeller() && ($user->seller->id === $productVariant->product->shop->seller_id) ||
            ($user->isAdmin() && $user->admin->role == AdminRoleEnum::SUPER_ADMIN->value);
    }
}

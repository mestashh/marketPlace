<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
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
        return true;
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
        return $user?->seller?->shop?->id === $productVariant->product?->shop_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductVariant $productVariant): bool
    {
        return ($user->seller?->shop?->id === $productVariant->product?->shop_id) || ($user->isAdmin() && $user->admin->role == AdminRoleEnum::SUPER_ADMIN->value);
    }
}

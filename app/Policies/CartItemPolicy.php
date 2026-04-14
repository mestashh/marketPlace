<?php

namespace App\Policies;

use App\Enums\StatusEnum;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartItemPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ProductVariant $productVariant): bool
    {
        return $productVariant->access_status == StatusEnum::ACCESS->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CartItem $cartItem): bool
    {
        return $user->id == $cartItem->cart->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CartItem $cartItem): bool
    {
        return $user->id == $cartItem->cart->user_id;
    }
}

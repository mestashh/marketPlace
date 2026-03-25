<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id;
    }
}

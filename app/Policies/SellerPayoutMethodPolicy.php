<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Models\SellerPayoutMethod;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SellerPayoutMethodPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return ($user->isAdmin() && $user->admin->role == AdminRoleEnum::SUPER_ADMIN->value) || $user->isSeller();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SellerPayoutMethod $sellerPayoutMethod): bool
    {
        return $user->isAdmin() && $user->admin->role == AdminRoleEnum::SUPER_ADMIN->value;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSeller();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SellerPayoutMethod $sellerPayoutMethod): bool
    {
        return $user->seller->id == $sellerPayoutMethod->seller_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SellerPayoutMethod $sellerPayoutMethod): bool
    {
        return $user->seller->id == $sellerPayoutMethod->seller_id;
    }
}

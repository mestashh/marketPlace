<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Models\PayoutMethod;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PayoutMethodPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSeller() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PayoutMethod $payoutMethod): bool
    {
        return $user->isSeller() || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
        // return $user->isAdmin() && $user->admin->role == AdminRoleEnum::SUPER_ADMIN->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PayoutMethod $payoutMethod): bool
    {
        return false;
        // return $user->isAdmin() && $user->admin->role == AdminRoleEnum::SUPER_ADMIN->value;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PayoutMethod $payoutMethod): bool
    {
        return false;
        // return $user->isAdmin() && $user->admin->role == AdminRoleEnum::SUPER_ADMIN->value;
    }
}

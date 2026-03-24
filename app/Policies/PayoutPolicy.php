<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PayoutPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payout $payout): bool
    {
        return $user->isAdmin() || $user->id == $payout->seller->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSeller() && $user->seller->withdrawable_balance > 100;
    }
}

<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $authUser, User $user): bool
    {
        return $authUser->isAdmin() || $authUser->id === $user->id;
    }

    public function create(?User $user): bool
    {
        return true;
    }

    public function update(User $authUser, User $user): bool
    {
        return $authUser->isAdmin() || $authUser->id === $user->id;
    }
}

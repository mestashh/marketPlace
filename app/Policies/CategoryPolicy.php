<?php

namespace App\Policies;

use App\Enums\AdminRoleEnum;
use App\Models\Category;
use App\Models\User;

class CategoryPolicy
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
    public function view(?User $user, Category $category): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() && $user->admin->role === AdminRoleEnum::SUPER_ADMIN->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->isAdmin() && $user->admin->role === AdminRoleEnum::SUPER_ADMIN->value;
    }
}

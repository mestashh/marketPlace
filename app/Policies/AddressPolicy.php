<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;

class AddressPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->isAdmin();
    }

    public function view(User $authUser, Address $address): bool
    {
        return $authUser->isAdmin() || $authUser->id === $address->user_id;
    }

    public function create(User $authUser): bool
    {
        return true;
    }

    public function update(User $authUser, Address $address): bool
    {
        return $authUser->isAdmin() || $authUser->id === $address->user_id;
    }

    public function delete(User $authUser, Address $address): bool
    {
        return $authUser->isAdmin() || $address->user_id === $authUser->id;
    }
}

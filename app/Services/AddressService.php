<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;

class AddressService
{
    public function index(User $user)
    {
        if ($user->isAdmin()) {
            return Address::query()->paginate(20);
        } else {
            return Address::where('user_id', $user->id)->get();
        }
    }

    public function store(User $user, array $data)
    {
        return Address::create([
            'user_id' => $user->id,
            'country' => $data['country'],
            'city' => $data['city'],
            'street' => $data['street'],
            'house' => $data['house'],
            'description' => $data['description'],
            'phone' => $data['phone'],
        ]);
    }
}

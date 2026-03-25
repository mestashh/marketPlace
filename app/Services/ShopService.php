<?php

namespace App\Services;

use App\Models\Shop;
use App\Models\User;

class ShopService
{
    public function create(User $user, array $data)
    {
        $shop = Shop::create([
            'name' => $data['name'],
            'seller_id' => $user->seller->id,
            'description' => $data['description'],
        ]);
        return $shop;
    }
}

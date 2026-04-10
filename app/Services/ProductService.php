<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;

class ProductService
{

    public function create(User $user, array $data)
    {
        return Product::create([
            'shop_id' => $user->seller->shop->id,
            'category_id' => $data['category_id'],
            'quantity' => $data['quantity'],
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
    }
}

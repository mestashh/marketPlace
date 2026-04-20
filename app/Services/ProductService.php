<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Events\Admin\ProductStatusChanged;
use App\Models\Product;
use App\Models\User;

class ProductService
{
    public function create(User $user, array $data): Product
    {
        $product = Product::create([
            'shop_id' => $user->seller->shop->id,
            'category_id' => $data['category_id'],
            'quantity' => $data['quantity'],
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        event(new ProductStatusChanged($product->id));

        return $product;
    }

    public function update(User $user, array $data, Product $product): Product
    {
        $product->update($data);
        $product->access_status = StatusEnum::CHECKING->value;
        $product->save();

        event(new ProductStatusChanged($product->id));

        return $product;
    }
}

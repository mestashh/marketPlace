<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ProductVariant;

class CartItemService
{
    public function store(Cart $cart, array $data)
    {
        $product = ProductVariant::findOrFail($data['product_variant_id']);

        return $cart->cartItems()->create([
            'product_variant_id' => $product->id,
            'quantity' => $data['quantity'],
            'price' => $product->price,
        ]);
    }
}

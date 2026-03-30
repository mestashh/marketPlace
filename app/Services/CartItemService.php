<?php

namespace App\Services;

use App\Exceptions\CartItem\ProductVariantStockException;
use App\Models\Cart;
use App\Models\ProductVariant;
use Throwable;

class CartItemService
{
    /**
     * @throws ProductVariantStockException
     * @throws Throwable
     */
    public function store(Cart $cart, array $data)
    {
        $product = ProductVariant::findOrFail($data['product_variant_id']);

        $cartItem = $cart->cartItems()
            ->where('product_variant_id', $data['product_variant_id'])
            ->first();

        $newQuantity = $cartItem
            ? $cartItem->quantity + $data['quantity']
            : $data['quantity'];

        if ($product->stock < $newQuantity) {
            throw new ProductVariantStockException;
        }

        if ($cartItem) {
            $cartItem->quantity = $newQuantity;
            $cartItem->save();

            return $cartItem;
        }

        return $cart->cartItems()->create([
            'product_variant_id' => $product->id,
            'quantity' => $data['quantity'],
            'price' => $product->price,
        ]);
    }
}

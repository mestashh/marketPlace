<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carts = Cart::all()->random(10);
        $productVariants = ProductVariant::all()->random(3);
        foreach ($carts as $cart) {
            foreach ($productVariants as $productVariant) {
                if ($cart->cartItems()->where('product_variant_id', $productVariant->id)->exists()) {
                    continue;
                }
                CartItem::factory()
                    ->count(1)
                    ->for($cart)
                    ->for($productVariant)
                    ->create();
            }
        }
    }
}

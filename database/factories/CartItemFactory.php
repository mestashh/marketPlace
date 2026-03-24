<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => Cart::inRandomOrder()->first()->id,
            'product_variant_id' => ProductVariant::inRandomOrder()->first()->id,
            'quantity' => fake()->biasedNumberBetween(1, 10),
            'price' => fake()->biasedNumberBetween(100, 10000),
        ];
    }
}

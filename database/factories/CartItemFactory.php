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
            'quantity' => fake()->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9]),
            'price' => fake()->randomElement([100, 145, 453, 5678, 140, 4670, 999]),
        ];
    }
}

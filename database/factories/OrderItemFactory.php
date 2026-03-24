<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\ShopOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::inRandomOrder()->first()->id,
            'quantity' => fake()->biasedNumberBetween(10, 1000),
            'price_at_purchase' => fake()->biasedNumberBetween(10, 10000),
        ];
    }
}

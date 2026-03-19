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
            'shop_order_id' => ShopOrder::factory()->create(),
            'product_variant_id' => ProductVariant::inRandomOrder()->first()->id,
            'quantity' => fake()->randomElement([10, 100, 1000, 1500, 2000, 999]),
            'price_at_purchase' => fake()->randomElement([599, 799, 999, 1500, 1999]),
        ];
    }
}

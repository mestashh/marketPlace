<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'access_status' => fake()->randomElement(StatusEnum::cases()),
            'name' => fake()->word(),
            'description' => fake()->text(50),
            'price' => fake()->biasedNumberBetween(100, 100000),
            'stock' => fake()->biasedNumberBetween(1, 10000),
            'sku' => fake()->unique()->numerify('SKU-######'),
        ];
    }
}

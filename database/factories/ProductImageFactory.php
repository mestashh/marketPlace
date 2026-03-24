<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
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
            'is_main' => fake()->randomElement([true, false]),
            'path' => fake()->unique()->uuid,
            'position' => fake()->biasedNumberBetween(1, 10),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()->id,
            'name' => fake()->word(),
            'quantity' => fake()->biasedNumberBetween(1, 100000),
            'description' => fake()->text(50),
            'access_status' => fake()->randomElement(StatusEnum::cases()),
        ];
    }
}

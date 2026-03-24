<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
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
            'name' => fake()->company(),
            'description' => fake()->text(50),
        ];
    }
}

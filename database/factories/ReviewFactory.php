<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => fake()->biasedNumberBetween(1, 5),
            'text' => fake()->text(),
            'access_status' => fake()->randomElement(StatusEnum::cases()),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class SellerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $balance = fake()->biasedNumberBetween(5000, 10000);
        return [
            'user_id' => User::factory(),
            'balance' => $balance,
            'access_status' => fake()->randomElement(StatusEnum::cases())->value,
            'withdrawable_balance' => $balance - fake()->biasedNumberBetween(1000, 5000),
            'TIN' => fake()->unique()->numerify('#########'),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\PayoutStatusEnum;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payout>
 */
class PayoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seller = Seller::inRandomOrder()->first()->id;

        return [
            'seller_id' => $seller,
            'status' => fake()->randomElement(PayoutStatusEnum::cases()),
            'amount' => fake()->biasedNumberBetween(500, 3000),
        ];
    }
}

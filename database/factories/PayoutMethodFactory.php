<?php

namespace Database\Factories;

use App\Enums\PayoutMethodEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PayoutMethod>
 */
class PayoutMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payout_method' => fake()->randomElement(PayoutMethodEnum::cases())->value,
        ];
    }
}

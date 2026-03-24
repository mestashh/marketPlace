<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::has('addresses')->inRandomOrder()->first();
        $address = $user->addresses()->inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'address_id' => $address,
            'total_price' => fake()->biasedNumberBetween(100, 10000),
            'status' => fake()->randomElement(OrderStatusEnum::cases())->value,
        ];
    }
}

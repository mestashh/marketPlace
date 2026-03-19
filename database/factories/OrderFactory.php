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
            'total_price' => fake()->randomElement([1000, 12000, 15000, 7000, 6500, 6900, 9999]),
            'status' => fake()->randomElement(OrderStatusEnum::cases()),
        ];
    }
}

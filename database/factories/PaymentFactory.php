<?php

namespace Database\Factories;

use App\Enums\PaymentStatusEnum;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id,
            'payment_method_id' => PaymentMethod::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(PaymentStatusEnum::cases())->value,
            'amount' => fake()->biasedNumberBetween(100, 10000),
        ];
    }
}

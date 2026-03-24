<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Shop;
use App\Models\ShopOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopOrderFactory extends Factory
{
    public function definition(): array
    {

        return [
            'status' => fake()->randomElement(OrderStatusEnum::cases())->value,
            'subtotal_price' => fake()->biasedNumberBetween(50, 5000),
        ];
    }
}

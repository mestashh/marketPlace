<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Shop;
use App\Models\ShopOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopOrderFactory extends Factory
{
    protected $model = ShopOrder::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory()->create(),
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'status' => fake()->randomElement(OrderStatusEnum::cases()),
            'subtotal_price' => fake()->randomElement([599, 1234, 3356, 9584, 163, 931]),
        ];
    }
}

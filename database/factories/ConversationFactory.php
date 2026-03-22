<?php

namespace Database\Factories;

use App\Enums\ConversationStatusEnum;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\ShopOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shop_order_id' => ShopOrder::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'seller_id' => Seller::inRandomOrder()->first()->id,
            'admin_id' => fake()->randomElement([null, Admin::inRandomOrder()->first()->id]),
            'status' => fake()->randomElement(ConversationStatusEnum::cases()),
        ];
    }
}

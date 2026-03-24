<?php

namespace Database\Factories;

use App\Enums\AdminRoleEnum;
use App\Enums\ConversationStatusEnum;
use App\Models\Admin;
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
        $shopOrder = ShopOrder::inRandomOrder()->first();
        $user = $shopOrder->order->user;
        $seller = $shopOrder->shop->seller;

        return [
            'shop_order_id' => $shopOrder->id,
            'user_id' => $user->id,
            'seller_id' => $seller->id,
            'admin_id' => fake()->randomElement([null, Admin::where('role', AdminRoleEnum::SUPPORT->value)->first()->id]),
            'status' => fake()->randomElement(ConversationStatusEnum::cases()),
        ];
    }
}

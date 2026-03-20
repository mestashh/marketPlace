<?php

namespace Database\Factories;

use App\Enums\PayoutStatusEnum;
use App\Models\PayoutMethod;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SellerPayoutMethod>
 */
class SellerPayoutMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $method = PayoutMethod::inRandomOrder()->first();
        $seller = Seller::inRandomOrder()->first()->id;

        return [
            'seller_id' => $seller,
            'payout_method_id' => $method->id,
            'status' => fake()->randomElement(PayoutStatusEnum::cases())->value,
            'details' => $this->detailsByMethod($method->payout_method),
        ];
    }

    private function detailsByMethod(string $method): array
    {
        return match ($method) {
            'card' => [
                'card_number' => fake()->numerify('###########'),
                'name' => fake()->name(),
                'bank' => fake()->company(),
            ],
            'sbp' => [
                'phone' => fake()->phoneNumber(),
                'bank' => fake()->company(),
            ],
            default => [
                'value' => fake()->word(),
            ]
        };
    }
}

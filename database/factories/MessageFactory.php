<?php

namespace Database\Factories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $conversation = Conversation::inRandomOrder()->first();

        return [
            'conversation_id' => $conversation->id,
            'user_id' => fake()->randomElement([$conversation->user_id, $conversation->seller_id]),
            'text' => fake()->word(),
        ];
    }
}

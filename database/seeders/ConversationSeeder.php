<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Conversation::factory(10)
            ->has(Message::factory()
                ->count(50)
                ->state(function (array $attributes, Conversation $conversation) {
                    return [
                        'user_id' => collect([
                            $conversation->user_id,
                            $conversation->seller_id,
                        ])->random(),
                    ];
                }))
            ->create();
    }
}

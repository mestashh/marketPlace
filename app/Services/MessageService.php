<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class MessageService
{
    public function store(User $user, array $data, Conversation $conversation)
    {
        return Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'text' => $data['text'],
        ]);
    }
}

<?php

namespace App\Exceptions\Conversation;

use Exception;

class ConversationExistsException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Conversation already open'], 422);
    }
}

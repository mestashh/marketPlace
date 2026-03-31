<?php

namespace App\Exceptions\Conversation;

use Exception;

class ConversationAdminException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Admin not requested or already connected']);
    }
}

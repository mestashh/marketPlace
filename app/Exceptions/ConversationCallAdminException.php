<?php

namespace App\Exceptions;

use Exception;

class ConversationCallAdminException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Admin already called'], 400);
    }
}

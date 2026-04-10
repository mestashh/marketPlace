<?php

namespace App\Exceptions\Email;

use Exception;

class CodeAlreadyInvalidException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Code already invalid, make a new one'], 422);
    }
}

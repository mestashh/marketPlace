<?php

namespace App\Exceptions\Email;

use Exception;

class EmailAlreadyVerifiedException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Email already verified'], 400);
    }
}

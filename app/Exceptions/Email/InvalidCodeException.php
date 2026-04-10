<?php

namespace App\Exceptions\Email;

use Exception;

class InvalidCodeException extends Exception
{
    public function render()
    {
        return response()->json(['message' => 'Invalid code, try one more'], 422);
    }
}

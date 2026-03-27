<?php

namespace App\Exceptions\Email;

use Exception;

class TooManyAttemptsException extends Exception
{
    public function render()
    {
        return response()->json(['message' => 'Too many attempts, making a new code'], 400);
    }
}

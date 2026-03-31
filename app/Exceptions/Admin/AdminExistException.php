<?php

namespace App\Exceptions\Admin;

use Exception;

class AdminExistException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'User already admin']);
    }
}

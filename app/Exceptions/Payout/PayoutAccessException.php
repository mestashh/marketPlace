<?php

namespace App\Exceptions\Payout;

use Exception;

class PayoutAccessException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'No access'], 422);
    }
}

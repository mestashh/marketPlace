<?php

namespace App\Exceptions\Review;

use Exception;

class ReviewNotFoundException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Review now found'], 422);
    }
}

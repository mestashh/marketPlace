<?php

namespace App\Exceptions\ProductImage;

use Exception;

class ProductImagePositionsFullException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'No free position for old main image'], 422);
    }
}

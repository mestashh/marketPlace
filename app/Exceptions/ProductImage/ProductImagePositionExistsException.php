<?php

namespace App\Exceptions\ProductImage;

use Exception;

class ProductImagePositionExistsException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Position already exists'], 400);
    }
}

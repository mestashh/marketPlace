<?php

namespace App\Exceptions;

use Exception;

class CategoryExistsException extends Exception
{
    public function render()
    {
        return response()->json(['message' => 'Category with this name exists'], 422);
    }
}

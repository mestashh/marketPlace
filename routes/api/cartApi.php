<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show']);
    Route::apiResource('items', CartItemController::class)
        ->parameters([
            'items' => 'item',
        ])
        ->except(['index', 'show']);
});

<?php

use App\Http\Controllers\CartItemController;

Route::prefix('carts')->group(function () {
    Route::post('items/{item}', [CartItemController::class, 'store']);
    Route::apiResource('items', CartItemController::class)
        ->except(['show', 'index', 'post']);
});

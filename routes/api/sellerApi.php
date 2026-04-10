<?php

use App\Http\Controllers\SellerController;

Route::apiResource('sellers', SellerController::class)
    ->except(['index', 'show', 'destroy']);

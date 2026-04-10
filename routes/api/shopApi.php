<?php

use App\Http\Controllers\ShopController;

Route::apiResource('shops', ShopController::class)->only(['update', 'store']);

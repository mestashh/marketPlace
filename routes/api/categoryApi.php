<?php

use App\Http\Controllers\CategoryController;

Route::apiResource('categories', CategoryController::class)
    ->only(['index', 'show']);

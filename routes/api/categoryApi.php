<?php

use App\Http\Controllers\CategoryController;

Route::ApiResource('/categories', CategoryController::class)->only(['update', 'store']);

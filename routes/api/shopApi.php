<?php

use App\Http\Controllers\ShopController;

Route::apiResource('shops', ShopController::class)->except(['index']);
Route::get('account/shop', [ShopController::class, 'showShopForOwner']);

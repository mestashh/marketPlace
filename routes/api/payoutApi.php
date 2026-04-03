<?php

use App\Http\Controllers\PayoutController;
use App\Http\Controllers\PayoutMethodController;
use App\Http\Controllers\SellerPayoutMethodController;

Route::apiResource('payoutMethod', PayoutMethodController::class);
Route::apiResource('payouts', PayoutController::class)
    ->except(['update', 'delete']);
Route::apiResource('seller/payoutMethods', SellerPayoutMethodController::class)
    ->parameters([
        'payoutMethods' => 'sellerPayoutMethod',
    ]);

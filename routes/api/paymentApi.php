<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;

Route::apiResource('paymentMethods', PaymentMethodController::class)
    ->except(['index', 'show']);
Route::apiResource('orders.payment', PaymentController::class)
    ->parameters([
        'orders' => 'order',
        'payment' => 'payment',
    ])
    ->scoped([
        'orders' => 'uuid',
        'payment' => 'uuid',
    ])
    ->except('index');
Route::get('payments', [PaymentController::class, 'index']);

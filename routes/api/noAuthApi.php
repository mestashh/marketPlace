<?php

use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;

Route::apiResource('paymentMethods', PaymentMethodController::class)
    ->only(['index', 'show']);

Route::apiResource('product/{product}/variant', ProductVariantController::class)
    ->parameters([
        'product' => 'product',
        'variant' => 'productVariant',
    ])
    ->scoped([
        'product' => 'uuid',
        'productVariant' => 'uuid',
    ])
    ->only(['index', 'show']);

Route::apiResource('product/{product}/image', ProductImageController::class)
    ->parameters([
        'product' => 'product',
        'image' => 'productImage',
    ])
    ->scoped([
        'product' => 'uuid',
        'productImage' => 'uuid',
    ])
    ->only(['index', 'show']);

Route::get('shops', [ShopController::class, 'index']); // all shops

Route::apiResource('sellers', SellerController::class)->only(['index', 'show']);

Route::post('auth/token', [UserController::class, 'getBearerToken']); // API token
Route::post('auth/register', [UserController::class, 'store']); // регистрация аккаунта


Route::apiResource('reviews', ReviewController::class) // Отзывы
    ->except(['destroy']);

Route::apiResource('products', ProductController::class)
    ->only(['index', 'show']);

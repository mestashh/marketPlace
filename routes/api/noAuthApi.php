<?php

use App\Http\Controllers\CategoryController;
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


Route::apiResource('products', ProductController::class) // Товары
    ->only(['index', 'show']);

Route::apiResource('products/{product}/variants', ProductVariantController::class)
    ->parameters([
        'product' => 'product',
        'variants' => 'productVariant',
    ])
    ->scoped([
        'product' => 'uuid',
        'productVariant' => 'uuid',
    ])
    ->only(['index', 'show']);

Route::apiResource('products/{product}/images', ProductImageController::class)
    ->parameters([
        'product' => 'product',
        'images' => 'productImage',
    ])
    ->scoped([
        'product' => 'uuid',
        'productImage' => 'uuid',
    ])
    ->only(['index', 'show']);

Route::apiResource('shops', ShopController::class)->only(['index', 'show']);

Route::apiResource('sellers', SellerController::class)->only(['index', 'show']);

Route::post('auth/token', [UserController::class, 'getBearerToken']); // API token
Route::post('auth/register', [UserController::class, 'store']); // регистрация аккаунта


Route::apiResource('products/{product}/reviews', ReviewController::class) // Отзывы
    ->except(['destroy']);

Route::apiResource('categories', CategoryController::class)
    ->only(['index', 'show']);


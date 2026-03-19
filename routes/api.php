<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PayoutMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/token', [UserController::class, 'getBearerToken']);
    Route::post('/register', [UserController::class, 'store']);

    Route::get('/shop', [ShopController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('address', AddressController::class);
        Route::apiResource('admin', AdminController::class);
        Route::apiResource('seller/shop', ShopController::class);
        Route::apiResource('payoutMethod', PayoutMethodController::class);

        Route::patch('product/status/{product}', [ProductController::class, 'changeStatus']);
        Route::patch('user/status/{user}', [UserController::class, 'changeStatus']);
        Route::patch('shop/status/{shop}', [ShopController::class, 'changeStatus']);
        Route::patch('seller/status/{seller}', [SellerController::class, 'changeStatus']);

        Route::get('cart', [CartController::class, 'show']);
        Route::apiResource('cart/items', CartItemController::class)
            ->except(['show', 'index']);

        Route::apiResource('seller', SellerController::class)
            ->except('index');
        Route::apiResource('category', CategoryController::class)
            ->except(['index', 'show']);
        Route::apiResource('product', ProductController::class)
            ->except(['index', 'show']);
        Route::apiResource('paymentMethod', PaymentMethodController::class)
            ->except(['index', 'show']);

        Route::apiResource('product.variant', ProductVariantController::class)
            ->parameters([
                'product' => 'product',
                'variant' => 'productVariant',
            ])
            ->scoped([
                'product' => 'uuid',
                'productVariant' => 'uuid',
            ])
            ->except(['index', 'show']);

        Route::apiResource('product.image', ProductImageController::class)
            ->parameters([
                'product' => 'product',
                'image' => 'productImage',
            ])
            ->scoped([
                'product' => 'uuid',
                'productImage' => 'uuid',
            ])
            ->except('index', 'show');

        Route::apiResource('me/orders', OrderController::class);
    });
    Route::apiResource('category', CategoryController::class)
        ->only(['index', 'show']);
    Route::apiResource('product', ProductController::class)
        ->only(['index', 'show']);
    Route::apiResource('paymentMethod', PaymentMethodController::class)
        ->only(['index', 'show']);

    Route::apiResource('product.variant', ProductVariantController::class)
        ->parameters([
            'product' => 'product',
            'variant' => 'productVariant',
        ])
        ->scoped([
            'product' => 'uuid',
            'productVariant' => 'uuid',
        ])
        ->only(['index', 'show']);

    Route::apiResource('product.image', ProductImageController::class)
        ->parameters([
            'product' => 'product',
            'image' => 'productImage',
        ])
        ->scoped([
            'product' => 'uuid',
            'productImage' => 'uuid',
        ])
        ->only(['index', 'show']);
});

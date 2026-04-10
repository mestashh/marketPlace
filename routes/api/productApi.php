<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductVariantController;

Route::apiResource('products', ProductController::class)
    ->except(['index', 'show', 'destroy']);

Route::apiResource('products/{product}/variants', ProductVariantController::class)
    ->parameters([
        'product' => 'product',
        'variant' => 'productVariant',
    ])
    ->scoped([
        'product' => 'uuid',
        'productVariant' => 'uuid',
    ])
    ->except(['index', 'show']);
Route::apiResource('products/{product}/images', ProductImageController::class)
    ->parameters([
        'product' => 'product',
        'image' => 'productImage',
    ])
    ->scoped([
        'product' => 'uuid',
        'productImage' => 'uuid',
    ])
    ->except('index', 'show');

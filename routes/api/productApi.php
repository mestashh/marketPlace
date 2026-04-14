<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductVariantController;

Route::apiResource('products', ProductController::class)
    ->except(['index', 'show', 'destroy']);

Route::apiResource('products/{product}/variants', ProductVariantController::class)
    ->parameters([
        'product' => 'product',
        'variants' => 'productVariant',
    ])
    ->scoped([
        'product' => 'uuid',
        'productVariant' => 'uuid',
    ])
    ->except(['index', 'show', 'destroy']);
Route::apiResource('products/{product}/images', ProductImageController::class)
    ->parameters([
        'product' => 'product',
        'images' => 'productImage',
    ])
    ->scoped([
        'product' => 'uuid',
        'productImage' => 'uuid',
    ])
    ->except('index', 'show');

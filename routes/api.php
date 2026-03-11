<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/token', [UserController::class, 'getBearerToken']);
Route::post('/register', [UserController::class, 'store']);

Route::middleware('auth:sanctum')
    ->apiResource('users', UserController::class);

Route::middleware('auth:sanctum')
    ->apiResource('address', AddressController::class);

Route::middleware('auth:sanctum')
    ->apiResource('admin', AdminController::class);
Route::middleware('auth:sanctum')
    ->group(function () {
        Route::patch('product/status/{product}', [ProductController::class, 'changeStatus']);
        Route::patch('user/status/{user}', [UserController::class, 'changeStatus']);
        Route::patch('shop/status/{shop}', [ShopController::class, 'changeStatus']);
        Route::patch('seller/status/{seller}', [SellerController::class, 'changeStatus']);
    });

Route::middleware('auth:sanctum')
    ->apiResource('seller', SellerController::class)
    ->except('index');

Route::get('/shop', [ShopController::class, 'index']);
Route::middleware('auth:sanctum')
    ->apiResource('seller/shop', ShopController::class);

Route::apiResource('category', CategoryController::class)
    ->only(['index', 'show']);
Route::middleware('auth:sanctum')
    ->apiresource('category', CategoryController::class)
    ->except(['index', 'show']);

Route::apiResource('product', ProductController::class)
    ->only(['index', 'show']);
Route::middleware('auth:sanctum')
    ->apiResource('product', ProductController::class)
    ->except(['index', 'show']);

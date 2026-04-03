<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Без авторизации
    require __DIR__ . '/api/noAuthApi.php';

    // Авторизация
    Route::middleware('auth:sanctum')->group(function () {
        require __DIR__ . '/api/addressApi.php';
        require __DIR__ . '/api/adminApi.php';
        require __DIR__ . '/api/cartApi.php';
        require __DIR__ . '/api/categoryApi.php';
        require __DIR__ . '/api/conversationApi.php';
        require __DIR__ . '/api/messageApi.php';
        require __DIR__ . '/api/orderApi.php';
        require __DIR__ . '/api/paymentApi.php';
        require __DIR__ . '/api/payoutApi.php';
        require __DIR__ . '/api/productApi.php';
        require __DIR__ . '/api/sellerApi.php';
        require __DIR__ . '/api/shopApi.php';
        require __DIR__ . '/api/userApi.php';
    });
});

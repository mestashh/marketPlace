<?php

use App\Http\Controllers\AdminController;

Route::apiResource('admins', AdminController::class);
Route::post('conversation/take', [AdminController::class, 'joinConversation']);
Route::patch('change-status', [AdminController::class, 'changeStatus']);

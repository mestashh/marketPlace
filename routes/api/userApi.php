<?php

use App\Http\Controllers\UserController;

Route::apiResource('users', UserController::class);
Route::post('users/email/verify', [UserController::class, 'verifyEmail']);

<?php

use App\Http\Controllers\MessageController;

Route::apiResource('conversation.messages', MessageController::class)
    ->except(['update', 'destroy']);

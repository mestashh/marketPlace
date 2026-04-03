<?php

use App\Http\Controllers\ConversationController;

Route::apiResource('conversations', ConversationController::class)->except(['destroy']);
Route::post('conversations/{conversation}/close', [ConversationController::class, 'close']);
Route::post('conversations/{conversation}/call-admin', [ConversationController::class, 'callAdmin']);

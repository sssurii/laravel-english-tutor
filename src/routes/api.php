<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\SessionController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::prefix('v1')->group(function (): void {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/sessions/start', [SessionController::class, 'start']);
        Route::post('/sessions/{session}/end', [SessionController::class, 'end']);
        Route::get('/sessions/history', [SessionController::class, 'history']);
        Route::get('/sessions/{session}/transcripts', [SessionController::class, 'transcripts']);

        Route::post('/chat/message', [ChatController::class, 'message']);
    });
});

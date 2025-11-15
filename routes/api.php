<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\AuthTokenController;

Route::prefix('v1')->group(function () {

    Route::post('login', [AuthTokenController::class, 'login']);

    // rotas publicass
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::apiResource('posts', PostController::class)->only(['index', 'show']);
    Route::apiResource('tags', TagController::class)->only(['index', 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', UserController::class)->except(['index', 'show']);
        Route::apiResource('posts', PostController::class)->except(['index', 'show']);
        Route::apiResource('tags', TagController::class)->except(['index', 'show']);
        Route::post('logout', [AuthTokenController::class, 'logout']);
    });
});

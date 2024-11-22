<?php

use App\Http\Controllers\Api\Articles\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\User\UserNewsFeedController;
use App\Http\Controllers\Api\User\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('/user/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::get('/reset-password/{token}', [AuthController::class, 'resetIndex'])->name('password.reset');
        Route::post('/user/reset-password', [AuthController::class, 'reset'])->name('password.update');
    });

    Route::middleware(['auth:sanctum', 'throttle:30,1'])->group(function () {

        Route::prefix('user')->group(function () {
            Route::get('/preferences', [UserPreferenceController::class, 'index']);
            Route::post('/preferences', [UserPreferenceController::class, 'store']);
            Route::get('/news-feed', [UserNewsFeedController::class, 'index']);
        });

        Route::apiResource('articles', ArticleController::class);
    });
});

Route::fallback(static function () {
    return response()->json(['message' => 'Not Found'], 404);
});

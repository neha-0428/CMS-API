<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'getAuthenticatedUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(['checkRole:Admin'])->group(function () {
        Route::prefix('/category')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::get('/{id}', [CategoryController::class, 'show']);
            Route::post('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });
    });

    Route::post('/articles/{id}', [ArticleController::class, 'update']);
    Route::resource('/articles', ArticleController::class)->except(['update']);
});

<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [LoginController::class, 'getAuthenticatedUser']);
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::group(['prefix' => '/category'], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::post('/articles/{id}', [ArticleController::class, 'update']);
    Route::resource('/articles', ArticleController::class)->except(['update']);
});

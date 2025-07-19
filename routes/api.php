<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [LoginController::class, 'getAuthenticatedUser']);
    Route::post('/logout', [LoginController::class, 'logout']);
});

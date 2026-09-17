<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Авторизация пользователя
Route::post('/login', [LoginController::class, 'store']);

// Регистрация пользователя
Route::post('/registration', [RegisterController::class, 'store']);

<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Авторизация пользователя
Route::post('/login', [LoginController::class, 'login']);

// Регистрация пользователя
Route::post('/registration', [RegisterController::class, 'store']);

// Таски
// Получение всех тасков
Route::get('/tasks', [TaskController::class, 'index']);

// Создание таска
Route::post('/tasks', [TaskController::class, 'store']);

// Обновление таска
Route::put('/tasks/{id}', [TaskController::class, 'update']);

// Удаление таска
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

//Напоминание
// Установить или изменить напоминание
Route::post('/tasks/{id}/reminder', [ReminderController::class, 'store']);

// Удаление напоминания
Route::delete('/tasks/{id}/reminder', [ReminderController::class, 'destroy']);

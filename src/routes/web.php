<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CommentController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store']);
    Route::delete('/tasks/{task}/comments/{comment}', [CommentController::class, 'destroy']);
    Route::get('/tasks/create', [TaskController::class, 'create']);
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit']);
});
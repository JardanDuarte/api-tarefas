<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/generateToken', [AuthController::class, 'generateToken']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('tasks', TaskController::class);

        Route::get('tasks/{task_id}/comments', [CommentController::class, 'index']);
        Route::post('tasks/{task_id}/comments', [CommentController::class, 'store']);
        Route::delete('tasks/{task_id}/comments/{comment_id}', [CommentController::class, 'destroy']);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');


// Tasks
Route::get('/tasks', [TasksController::class, 'index'])->middleware('auth:sanctum');
Route::post('/tasks', [TasksController::class, 'store'])->middleware('auth:sanctum');
Route::get('/tasks/{task}', [TasksController::class, 'show'])->middleware('auth:sanctum');
Route::put('/tasks/{task}', [TasksController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/tasks/{task}', [TasksController::class, 'destroy'])->middleware('auth:sanctum');

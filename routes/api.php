<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tasks/create', [TaskController::class, 'createTask'])->name('task.create');
Route::get('/tasks', [TaskController::class, 'getAllTasks'])->name('task.getAll');
Route::get('/tasks/export', [TaskController::class, 'exportTasks']);
Route::get('/tasks/chart', [TaskController::class, 'getChartData']);

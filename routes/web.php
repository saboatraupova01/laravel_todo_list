<?php

use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
Route::patch('/tasks/{task}', [TaskController::class, 'update']);
Route::post('/tasks/ajax', [TaskController::class, 'storeAjax']);

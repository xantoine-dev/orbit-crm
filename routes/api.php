<?php

use App\Http\Controllers\Api\V1\ClientApiController;
use App\Http\Controllers\Api\V1\ProjectApiController;
use App\Http\Controllers\Api\V1\TaskApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->as('api.v1.')->group(function () {
    Route::apiResource('clients', ClientApiController::class);
    Route::apiResource('projects', ProjectApiController::class);
    Route::apiResource('tasks', TaskApiController::class);
    Route::patch('tasks/{task}/status', [TaskApiController::class, 'updateStatus'])->name('tasks.status');
});

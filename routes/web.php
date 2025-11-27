<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/search', SearchController::class)->name('search');

    Route::resource('clients', ClientController::class);
    Route::post('clients/{client}/restore', [ClientController::class, 'restore'])
        ->name('clients.restore')
        ->middleware('can:restore-records');

    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/restore', [ProjectController::class, 'restore'])
        ->name('projects.restore')
        ->middleware('can:restore-records');

    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/restore', [TaskController::class, 'restore'])
        ->name('tasks.restore')
        ->middleware('can:restore-records');
    Route::post('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');

    Route::resource('users', UserController::class)->middleware('can:manage-users');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

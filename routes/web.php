<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')
->group(function() {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login/authentication', [AuthController::class, 'authenticate'])->middleware('throttle:login')->name('authenticate');
});

Route::middleware('auth')
->group(function() {
    Route::middleware('role:superadmin')
    ->prefix('sa')
    ->name('superadmin.')
    ->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::middleware('role:admin')
    ->prefix('ad')
    ->name('admin.')
    ->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::middleware('role:user')
    ->prefix('us')
    ->name('user.')
    ->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
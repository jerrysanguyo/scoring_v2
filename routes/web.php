<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cms\CriteriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParticipantController;
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
        // Route::resource('criteria', CriteriaController::class)->middleware('merge_cms:criterias,criteria'); // merge_cms:table_name,resource
        Route::resource('criteria', CriteriaController::class)->parameters(['criteria' => 'criteria']);;
        Route::get('criteria/{criteria}/show/json', [CriteriaController::class, 'showJson'])->name('criteria.show.json');
        Route::resource('participant', ParticipantController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('/scores', [DashboardController::class, 'store'])->name('scores.store');
        Route::get('/scores/{participant}/{criteria}', [DashboardController::class, 'showForCriteria'])->name('scores.showForCriteria');
        
        Route::post('/criteria/{criteria}/lock',   [CriteriaController::class, 'lock'])->name('criteria.lock');
        Route::post('/criteria/{criteria}/unlock', [CriteriaController::class, 'unlock'])->name('criteria.unlock');
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
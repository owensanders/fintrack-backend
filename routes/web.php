<?php

use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserExpensesController;
use App\Http\Controllers\UserSavingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Authenticated Routes
Route::middleware(['auth:sanctum'])->group(function () {

    // User Profile Routes
    Route::put('my-profile', [UserController::class, 'update'])->name('my-profile.update');

    // User Expenses Routes
    Route::prefix('user-expenses')->name('user-expenses.')->group(function () {
        Route::post('/', [UserExpensesController::class, 'store'])->name('store');
        Route::put('/{id}', [UserExpensesController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserExpensesController::class, 'destroy'])->name('destroy');
    });

    // User Savings Routes
    Route::prefix('user-savings')->name('user-savings.')->group(function () {
        Route::post('/', [UserSavingsController::class, 'store'])->name('store');
        Route::put('/{id}', [UserSavingsController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserSavingsController::class, 'destroy'])->name('destroy');
    });

    //Auth
    Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
});

//Guest Routes
Route::post('/register', [AuthenticatedSessionController::class, 'register'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'login'])
    ->middleware('guest')
    ->name('login');

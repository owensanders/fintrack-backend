<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::patch('my-profile/update', [UserController::class, 'update']);

require __DIR__.'/auth.php';

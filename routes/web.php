<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\API\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Route::post('/register', [UserController::class, 'register']);
// Route::post('/login', [UserController::class, 'login']);

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/user', [UserController::class, 'fetch']);
//     Route::post('/logout', [UserController::class, 'logout']);
// });

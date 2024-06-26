<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/login', function () {
        return view('auth.login');
    });

    Route::get('/register', function () {
        return view('auth.register');
    });
});

// Route::post('/register', [AuthController::class, 'register'])->name('register');


// Route::post('/login', [AuthController::class, 'login'])->name('login');

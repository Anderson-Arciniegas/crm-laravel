<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

// Post
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Get
Route::get('/users', [UserController::class, 'index'])->name('users.getAll');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.getById');
Route::get('/users/role/{role}', [UserController::class, 'findByRole'])->name('users.getByRole');

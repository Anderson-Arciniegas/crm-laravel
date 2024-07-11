<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('home');
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
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Get
Route::get('/users', [UserController::class, 'getAll'])->name('users.getAll');
Route::get('/users/{id}', [UserController::class, 'getById'])->name('users.getById');
Route::get('/users/role/{role}', [UserController::class, 'getByRole'])->name('users.getByRole');
Route::get('/users/client/search/{name}', [UserController::class, 'getClientsByName'])->name('users.getClientsByName');

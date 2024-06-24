<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});


/**
 * Route to register a new user.
 */
Route::post('/register', [AuthController::class, 'register'])->name('register');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;

/**
 * Routes that require authentication and admin role.
 */
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class])->group(function () {
    Route::get('/admin', [AuthController::class, 'showAdmin'])->name('admin');

    Route::get('/clients', [UserController::class, 'showClients'])->name('clients');

    Route::get('/clients/{id}', [UserController::class, 'showClientDetails'])->name('details');


    Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard', ['user' => auth()->user()]);
    })->name('dashboard');

    Route::get('/change-password', function () {
        return view('profile.change-password', ['user' => auth()->user()]);
    })->name('profile.change-password');

    Route::get('/clients/{id}/edit', [UserController::class, 'editClient'])->name('edit');

    Route::put('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
});

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
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('auth.changePassword');


// Get
Route::get('/users', [UserController::class, 'getAll'])->name('users.getAll');
Route::get('/users/{id}', [UserController::class, 'getById'])->name('users.getById');
Route::get('/users/role/{role}', [UserController::class, 'getByRole'])->name('users.getByRole');
Route::get('/users/client/search', [UserController::class, 'getClientsByName'])->name('users.getClientsByName');

//Tickets
Route::post('/ticket', [TicketsController::class, 'create'])->name('tickets.create');
Route::get('/ticket/not-assigned', [TicketsController::class, 'getNotAssigned'])->name('tickets.getNotAssigned');
Route::get('/ticket/assigned', [TicketsController::class, 'getAssigned'])->name('tickets.getAssigned');
Route::get('/ticket/completed', [TicketsController::class, 'getCompleted'])->name('tickets.getCompleted');
Route::get('/ticket/{id}', [TicketsController::class, 'getById'])->name('tickets.getById');
Route::put('/ticket/{id}', [TicketsController::class, 'update'])->name('tickets.update');
Route::put('/ticket/{id}', [TicketsController::class, 'assign'])->name('tickets.assign');
Route::put('/ticket/{id}/status/{status}', [TicketsController::class, 'manage'])->name('tickets.manage');

//Projects
Route::get('/projects', [ProjectsController::class, 'search'])->name('projects.search');
Route::post('/projects', [ProjectsController::class, 'create'])->name('projects.create');
Route::get('/projects/{id}', [ProjectsController::class, 'getById'])->name('projects.getById');
Route::put('/projects/{id}', [ProjectsController::class, 'update'])->name('projects.update');
Route::put('/projects/{id}', [ProjectsController::class, 'addUserToProjectTeam'])->name('projects.addUserToProjectTeam');
Route::delete('/projects/{id}', [ProjectsController::class, 'destroy'])->name('projects.destroy');

//Tasks
Route::get('/tasks', [TasksController::class, 'search'])->name('tasks.search');
Route::post('/tasks', [TasksController::class, 'create'])->name('tasks.create');
Route::get('/tasks/{id}', [TasksController::class, 'getById'])->name('tasks.getById');
Route::put('/tasks/{id}', [TasksController::class, 'update'])->name('tasks.update');
Route::put('/tasks/{id}/complete', [TasksController::class, 'complete'])->name('tasks.complete');
Route::put('/tasks/{id}/user/{id_user}', [TasksController::class, 'assignTask'])->name('tasks.assignTask');
Route::delete('/tasks/{id}', [TasksController::class, 'destroy'])->name('tasks.destroy');

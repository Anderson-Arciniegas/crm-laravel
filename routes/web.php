<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

/**
 * Routes that require authentication and admin role.
 */
Route::middleware(['auth', \App\Http\Middleware\CheckRole::class])->group(function () {
    Route::get('/admin', [AuthController::class, 'showAdmin'])->name('admin');


    Route::get('/tickets', [TicketsController::class, 'getAll'])->name('tickets.assigned');
    Route::put('/ticket/{id}/status/{status}', [TicketsController::class, 'manage'])->name('tickets.manage');
    Route::get('/clients', [UserController::class, 'showClients'])->name('clients');

    Route::get('/clients/{id}', [UserController::class, 'showClientDetails'])->name('details');

    Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');

    Route::get('/clients/{id}/edit', [UserController::class, 'editClient'])->name('edit');

    Route::get('/users', [UserController::class, 'getAll'])->name('users.getAll');
    Route::get('/users/{id}', [UserController::class, 'getById'])->name('users.getById');
    Route::get('/users/role/{role}', [UserController::class, 'getByRole'])->name('users.getByRole');
    Route::get('/users/client/search', [UserController::class, 'getClientsByName'])->name('users.getClientsByName');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user) {
            return view('dashboard.dashboard', ['user' => $user]);
        }
        return view('auth.login');
    })->name('dashboard');

    Route::get('/create-ticket', function () {
        $user = auth()->user();
        if ($user) {
            return view('tickets.create-tickets', ['user' => $user]);
        }
        return view('auth.login');
    })->name('tickets.create-tickets');

    Route::get('/my-ticket', function () {
        $user = auth()->user();
        if ($user) {
            $tickets = Ticket::where('id_user_creator', '=', $user->id)->get();
            return view('tickets.my-tickets', ['user' => $user, 'tickets' => $tickets]);
        }
        return view('auth.login');
    })->name('tickets.my-tickets');

    Route::get('/change-password', function () {
        $user = auth()->user();
        if ($user) {
            return view('profile.change-password', ['user' => $user]);
        }
        return view('auth.login');
    })->name('profile.change-password');

    Route::put('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');

    Route::get('/projects', [ProjectsController::class, 'showProjects'])->name('projects');

    Route::get('/projects/{id}', [ProjectsController::class, 'showProjectDetails'])->name('projects.details');

    Route::get('/projects/{id}/team', [ProjectsController::class, 'showProjectTeam'])->name('projects.team');

    Route::get('/projects/{id}/team/add', [ProjectsController::class, 'showProjectAddMember'])->name('projects.member');

    Route::get('/projects/{id}/edit', [ProjectsController::class, 'showProjectEdit'])->name('projects.edit');


    Route::get('/projects/create', function () {
        return view('projects.create', ['user' => auth()->user()]);
    })->name(
        'createProject'
    );

    Route::get('/get-projects', [ProjectsController::class, 'search'])->name('projects.search');

    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('auth.changePassword');


    //Projects
    Route::post('/projects', [ProjectsController::class, 'create'])->name('projects.create');
    Route::get('/get-projects/{id}', [ProjectsController::class, 'getById'])->name('projects.getById');
    Route::put('/projects/{id}', [ProjectsController::class, 'update'])->name('projects.update');
    Route::put('/projects/member/{id}', [ProjectsController::class, 'addUserToProjectTeam'])->name('projects.addUserToProjectTeam');

    Route::put('/projects/{projectId}/member/{userId}/delete', [ProjectsController::class, 'deleteUserMember'])->name('projects.deleteUserProjectTeam');

    Route::delete('/projects/{id}', [ProjectsController::class, 'destroy'])->name('projects.destroy');
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

    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

//Tickets
Route::post('/ticket', [TicketsController::class, 'create'])->name('tickets.create');
Route::get('/ticket/my-tickets', [TicketsController::class, 'getMyTickets'])->name('tickets.getMyTickets');
Route::get('/ticket/not-assigned', [TicketsController::class, 'getNotAssigned'])->name('tickets.getNotAssigned');
Route::get('/ticket/assigned', [TicketsController::class, 'getAssigned'])->name('tickets.getAssigned');
Route::get('/ticket/completed', [TicketsController::class, 'getCompleted'])->name('tickets.getCompleted');
Route::get('/ticket/{id}', [TicketsController::class, 'getById'])->name('tickets.getById');
Route::put('/ticket/{id}', [TicketsController::class, 'update'])->name('tickets.update');
Route::put('/ticket/{id}', [TicketsController::class, 'assign'])->name('tickets.assign');
Route::put('/ticket/{id}/status/{status}', [TicketsController::class, 'manage'])->name('tickets.manage');

//Tasks
Route::get('/tasks', [TasksController::class, 'search'])->name('tasks.search');
Route::post('/tasks', [TasksController::class, 'create'])->name('tasks.create');
Route::get('/tasks/{id}', [TasksController::class, 'getById'])->name('tasks.getById');
Route::put('/tasks/{id}', [TasksController::class, 'update'])->name('tasks.update');
Route::put('/tasks/{id}/complete', [TasksController::class, 'complete'])->name('tasks.complete');
Route::put('/tasks/{id}/user/{id_user}', [TasksController::class, 'assignTask'])->name('tasks.assignTask');
Route::delete('/tasks/{id}', [TasksController::class, 'destroy'])->name('tasks.destroy');

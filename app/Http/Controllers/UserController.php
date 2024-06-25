<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return all users where status is not deleted
        return User::where('status', '!=', 'deleted')->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Return user by id and status is not deleted
        return User::where('id', $id)->where('status', '!=', 'deleted')->first();
    }

    /* 
     * Get users admin
     */
    public function getUsersByRole(int $roleId) {
        $users = User::whereHas('user_roles', function ($query) use ($roleId) {
            $query->where('id', $roleId);
        })->get();
    
        return $users;
    }

    // En UserController.php
    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'birth_date' => $data['birth_date'],
            'client_type' => $data['client_type'],
            'address' => $data['address'],
        ]);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the role of a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserRole(Request $request)
    {
        // Validar el request
        $request->validate([
            'idUser' => 'required|exists:users,id',
            'idRol' => 'required|exists:roles,id', // Asegura que el idRol exista en la tabla de roles
        ]);

        // Buscar el usuario por ID
        $user = User::find($request->idUser);

        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found');
        }

        // Verificar si el idRol existe en la tabla user_roles para este usuario
        $userRole = UserRole::where('id_user', $user->id)->first();

        if ($userRole) {
            // Si existe, actualizar el idRol
            $userRole->id_role = $request->idRol;
            $userRole->save();
        } else {
            // Si no existe, crear una nueva asociación en user_roles
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $request->idRol,
            ]);
        }

        return redirect()->route('admin')->with('success', 'User role updated successfully');
    }

    /**
     * Delete a user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    function delete(User $user)
    {
        $user->update(['status' => 'deleted']);
        $users = $this->getUsers();
        return redirect()->route('admin', ['users' => $users])->with('success', 'User deleted successfully');
    }
}

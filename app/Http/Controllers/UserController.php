<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRol;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAll()
    {
        // Return all users where status is not deleted
        return User::where('status', '!=', 'deleted')->get();
    }

    /**
     * Display the specified resource.
     */
    public function getById(string $id)
    {
        // Return user by id and status is not deleted
        return User::where('id', $id)->where('status', '!=', 'deleted')->first();
    }

    /* 
     * Get users admin
     */
    public function getByRole(int $roleId)
    {
        $users = User::whereHas('user_roles', function ($query) use ($roleId) {
            $query->where('id', $roleId);
        })->get();

        return $users;
    }

    public function getClientsByName(string $name)
    {
        $users = User::where('name', 'like', "%{$name}%")
                    ->whereHas('user_roles', function ($query) {
                        $query->where('code', 'CLI02');
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
            'phone' => $data['phone'],
        ]);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'address', 'phone']));
        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
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
        $userRole = UserRol::where('id_user', $user->id)->first();

        if ($userRole) {
            // Si existe, actualizar el idRol
            $userRole->id_role = $request->idRol;
            $userRole->save();
        } else {
            // Si no existe, crear una nueva asociación en user_roles
            UserRol::create([
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
        $user->update(['status' => 'Deleted']);
        $users = $this->getUsers();
        return redirect()->route('admin', ['users' => $users])->with('success', 'User deleted successfully');
    }
}

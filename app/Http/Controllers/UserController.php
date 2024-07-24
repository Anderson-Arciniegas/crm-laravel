<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function showClients()
    {
        $users = $this->getClients();

        return view('clients.clients', ['users' => $users]);
    }

    public function editClient($id)
    {
        $user = User::findOrFail($id);

        return view('clients.edit', ['user' => $user]);
    }



    public function showClientDetails($id)
    {
        $user = User::findOrFail($id);
        return view('clients.details', ['user' => $user]);
    }

    /**
     * Display a listing of the resource.
     */
    public function getAll()
    {
        // Return all users where status is not Deleted
        return User::where('status', '!=', 'Deleted')->get();
    }

    public function getClients()
    {
        return User::where('status', '!=', 'Deleted')->whereHas('roles', function ($query) {
            $query->where('code', 'CLI02');
        })->get();
    }

    /**
     * Display the specified resource.
     */
    public function getById(string $id)
    {
        // Return user by id and status is not Deleted
        return User::where('id', $id)->where('status', '!=', 'Deleted')->first();
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

    public function getClientsByName(Request $request)
    {
        $name = $request->query('search');
        $users = User::where('email', 'like', "%{$name}%")
            ->whereHas('roles', function ($query) {
                $query->where('code', 'CLI02');
            })->get();
        return view('clients.clients', ['users' => $users]);
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
        $user->update($request->only(['name', 'email', 'address', 'phone', 'client_type']), ['id_user_modification' => Auth::user()->id]);

        return redirect()->route('details', $id)->with('success', 'Usuario actualizado con éxito.');
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
        $user = User::findOrFail($id);

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
    function delete(string $id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'Deleted', 'id_user_modification' => Auth::user()->id]);
        return redirect()->route('clients')->with('success', 'User Deleted successfully');
    }
}

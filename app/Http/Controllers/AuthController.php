<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'birth_date' => 'required|date',
            'client_type' => 'required|in:person,business',
            'address' => 'required|in:string',
            'role_code' => 'required|exists:roles,code',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->client_type = $request->client_type;
        $user->birth_date = $request->birth_date;
        $user->address = $request->address;
        $code = $request->role_code;
        $user->password = Hash::make($request->password);

        if($user->save()){
            $credentials = $request->only('email', 'password');
            if(Auth::attempt($credentials)){
                $userLogged = Auth::user();
                $role = Rol::where('code', $code)->first();
                $userRole = new UserRole();
                $userRole->id_user = $user->id;
                $userRole->id_role = $role->id;
                $userRole->save();
                return redirect()->intended(route('home'));
            }
            return redirect()->intended(route('register'));
        }
        return redirect(route('register'))->with('error', 'Failed to create user');
    }
    /**
     * Recover Password
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function recoverPassword(Request $request){
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|string|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        $new_password = $request->new_password;
        if($user){
            $user->password = Hash::make($new_password);
            $user->save();
            return redirect(route('login'))->with('success', 'Password recovered successfully');
        }
        return redirect(route('recover-password'))->with('error', 'User not found');
    }

    /**
     * Change Password
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function changePassword(Request $request){
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed'
        ]);

        $user = Auth::user();
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        if(Hash::check($old_password, $user->password)){
            $user->password = Hash::make($new_password);
            $user->save();
            return redirect(route('home'))->with('success', 'Password changed successfully');
        }
        return redirect(route('change-password'))->with('error', 'Invalid password');
    }

    /**
     * Log in a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            $userLogged = Auth::user();
            return redirect()->intended(route('home'));
        }
        return redirect(route('login'))->with('error', 'Invalid credentials');
    }

    /**
     * Log out the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function logout(Request $request){
        Auth::logout();
        return redirect(route('login'));
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    function showAdmin()
    {
        $users = $this->getUsers();
        $roles = $this->getRoles();

        return view('dashboard.admin', ['users' => $users, 'roles' => $roles]);
    }

    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsers()
    {
        return User::all();
    }

    /**
     * Get all roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRoles()
    {
        return Rol::all();
    }

    /**
     * Delete a user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    function deleteUser(User $user)
    {
        $user->delete();
        $users = $this->getUsers();

        return redirect()->route('admin', ['users' => $users])->with('success', 'User deleted successfully');
    }

    /**
     * Update the role of a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->sync([$request->role_id]);

        return redirect()->route('admin')->with('success', 'User role updated successfully');
    }
}

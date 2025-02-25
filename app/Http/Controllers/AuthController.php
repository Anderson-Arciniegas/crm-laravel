<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $userController;
    protected $userRoleController;
    protected $roleController;

    public function __construct(
        UserController $userController,
        UserRoleController $userRoleController,
        RoleController $roleController,
    ) {
        $this->userController = $userController;
        $this->userRoleController = $userRoleController;
        $this->roleController = $roleController;
    }
    function index()
    {
        return view('auth.login');
    }
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
            'phone' => 'required|string|max:15',
            'password' => 'required|string',
            'birth_date' => 'required|date_format:Y-m-d',
            'client_type' => 'required|string',
            'address' => 'required|string',
            // 'role_code' => 'required|exists:roles,code',
        ]);

        $data = $request->only(['name', 'email', 'password', 'birth_date', 'client_type', 'address', 'phone']);
        $user = $this->userController->create($data);

        if ($user->save()) {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $userLogged = Auth::user();
                $role = $this->roleController->getByCode('CLI02');
                $this->userRoleController->create(['id_user' => $user->id, 'id_role' => $role->id]);
                return redirect(route('dashboard'))->with('success', __('User created successfully'));
            }
            return redirect(route('auth.register'))->with('error', 'Failed to create user');
        }

        return redirect(route('auth.register'))->with('error', 'Failed to create user');
    }

    /**
     * Recover Password
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function recoverPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|string|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();
        $new_password = $request->new_password;
        if ($user) {
            $user->password = Hash::make($new_password);
            $user->save();
            return redirect(route('auth.login'))->with('success', 'Password recovered successfully');
        }
        return redirect(route('recover-password'))->with('error', 'User not found');
    }

    /**
     * Log in a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->where('status', '!=', 'Deleted')->first();
        if (!$user) {
            return redirect(route('auth.login'))->with('error', 'User not found');
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $userLogged = Auth::user();
            $roles = $userLogged->roles;
            if ($roles[0]->code == 'ADM01') {
                return redirect()->intended(route('admin', ['user' => $userLogged]))->with('success', __('User logged in successfully'));
            } else if ($roles[0]->code == 'LEA03') {
                return redirect()->intended(route('dashboard', ['user' => $userLogged]))->with('success', __('User logged in successfully'));
            } else if ($roles[0]->code == 'CLI02') {
                return redirect()->intended(route('dashboard', ['user' => $userLogged]))->with('success', __('User logged in successfully'));
            }
        }
        return redirect(route('auth.login'))->with('error', 'Invalid credentials');
    }

    /**
     * Log out the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    function showAdmin()
    {
        $user = Auth::user();

        return view('dashboard.admin', ['user' => $user]);
    }

    function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:8',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->new_password);
        $user->id_user_modification = $user->id;

        if ($user->save()) {
            return back()->with('success', 'Contraseña cambiada con éxito.');
        } else {
            Log::info('Error al guardar la contraseña');
            return back()->with('error', 'Error al cambiar la contraseña.');
        };
    }
}

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
            'password' => 'required|string',
            'birth_date' => 'required|date_format:Y-m-d',
            'client_type' => 'required|string',
            'address' => 'required|string',
            // 'role_code' => 'required|exists:roles,code',
        ]);

        $data = $request->only(['name', 'email', 'password', 'birth_date', 'client_type', 'address']);
        $user = $this->userController->create($data);

        if ($user->save()) {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $userLogged = Auth::user();
                $role = $this->roleController->getByCode('CLI02');
                $this->userRoleController->create(['id_user' => $user->id, 'id_role' => $role->id]);
                return redirect(route('home'));
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

    // /**
    //  * Change Password
    //  * 
    //  * @param \Illuminate\Http\Request $request
    //  * @return \Illuminate\Http\RedirectResponse
    //  */
    // function changePassword(Request $request){
    //     $request->validate([
    //         'old_password' => 'required|string',
    //         'new_password' => 'required|string|confirmed'
    //     ]);

    //     $user = Auth::user();
    //     $old_password = $request->old_password;
    //     $new_password = $request->new_password;
    //     if(Hash::check($old_password, $user->password)){
    //         $user->password = Hash::make($new_password);
    //         $user->save();
    //         return redirect(route('home'))->with('success', 'Password changed successfully');
    //     }
    //     return redirect(route('change-password'))->with('error', 'Invalid password');
    // }

    /**
     * Log in a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function login(Request $request)
    {
        print_r('login');
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $userLogged = Auth::user();
            return redirect()->intended(route('home'));
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
        return redirect(route('auth.login'));
    }
}

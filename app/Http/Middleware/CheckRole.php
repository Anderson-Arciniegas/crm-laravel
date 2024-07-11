<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        print_r($role);
        if (!Auth::check() || !Auth::user()->userRoles->contains('id_role', $role)) {
            return redirect(route('dashboard'));
        }

        return $next($request);
    }
}

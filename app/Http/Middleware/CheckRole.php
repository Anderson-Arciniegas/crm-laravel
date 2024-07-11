<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->roles[0]->code !== 'AMD01') {
            return redirect(route('dashboard'));
        }

        return $next($request);
    }
}

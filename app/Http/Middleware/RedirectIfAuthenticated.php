<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            return match ($role) {
                'admin' => redirect('/admin/dashboard'),
                'user' => redirect('/home'),
                default => redirect('/home'),
            };
        }

        return $next($request);
    }
}

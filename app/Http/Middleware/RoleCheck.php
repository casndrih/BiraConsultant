<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::guard('web')->user();

        // Check if the user is authenticated and has the required role
        if ($user && $user->role == $role) {
            return $next($request);
        }

        // Return 403 for unauthorized access
        return abort(403, 'Unauthorized action.');
    }
}
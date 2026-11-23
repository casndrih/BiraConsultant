<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CompanyAccess
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('home');
        }

        // Allow if user is role 2 (Company)
        if ((int) $user->role === 2) {
            return $next($request);
        }

        // Allow if user is role 0 (Applicant) but has a registered company
        if ((int) $user->role === 0 && $user->company) {
            return $next($request);
        }

        // Allow if user is role 1 (Admin) — admin can access everything
        if ((int) $user->role === 1) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}

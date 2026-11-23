<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Checks the global "maintenance_mode" setting.
 * If it is enabled (value = "1") and the current user is NOT an admin (role 2),
 * the request is aborted with a 503 Service Unavailable response.
 *
 * Admin users (role = 2) bypass the maintenance mode so they can still access the
 * dashboard and perform site‑wide actions.
 */
class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the setting – use a simple query to avoid loading the full model.
        $maintenance = DB::table('qjobs_settings')
            ->where('key', 'maintenance_mode')
            ->value('value');

        // If maintenance is on …
        if ($maintenance === '1') {
            // Authenticated admin bypasses the block.
            if (auth()->check() && auth()->user()->role == 2) {
                return $next($request);
            }

            // Return a generic 503 page (you can customise the view if you like).
            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}
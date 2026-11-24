<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
     Schema::defaultStringLength(191);
     Paginator::defaultView('vendor.pagination.tailwind');

     /*
     |----------------------------------------------------------------------
     | Named rate limiters
     |----------------------------------------------------------------------
     | The framework's default guest throttle key is domain|ip only (no route
     | URI), so plain `throttle:5,1` on several auth routes would share ONE
     | bucket per IP. These named limiters key by route path so each auth
     | endpoint has its own brute-force budget.
     */
     RateLimiter::for('auth', function (Request $request) {
         return Limit::perMinute(5)->by('auth|'.$request->path().'|'.($request->user()?->getAuthIdentifier() ?: $request->ip()));
     });

     RateLimiter::for('lookup', function (Request $request) {
         return Limit::perMinute(30)->by('lookup|'.$request->path().'|'.$request->ip());
     });
    }
}

<?php

namespace App\Http\Middleware;

use App\Helpers\RecaptchaHelper;
use Closure;
use Illuminate\Http\Request;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {
        if (!RecaptchaHelper::isEnabled()) {
            return $next($request);
        }

        $token = $request->input('g-recaptcha-response');

        if (empty($token)) {
            return back()->with('error', 'Please complete the reCAPTCHA verification.')->withInput();
        }

        if (!RecaptchaHelper::verify($token)) {
            return back()->with('error', 'reCAPTCHA verification failed. Please try again.')->withInput();
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Services\AuthMethodSettings;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return match ((int) Auth::user()->role) {
                0 => redirect()->route('user.dashboard'),
                1 => redirect()->route('admin-dashboard'),
                2 => redirect()->route('company.dashboard'),
                default => redirect()->route('home'),
            };
        }

        return view('auth.login', [
            'emailLoginEnabled' => AuthMethodSettings::emailEnabled(),
            'googleLoginEnabled' => AuthMethodSettings::googleEnabled(),
        ]);
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password', [
            'emailLoginEnabled' => AuthMethodSettings::emailEnabled(),
        ]);
    }

    public function signIn(Request $request)
    {
        if (!AuthMethodSettings::emailEnabled()) {
            return back()->with('error', 'Email sign-in is disabled. Choose another enabled sign-in method.');
        }
        // Generic failure message everywhere: never reveal whether the email exists.
        $failure = fn () => back()->with('error', 'Invalid email or password.')->withInput($request->only('email'));

        $request->validate([
            'email' => ['required', 'email:filter', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        // Normalize the email so case differences never block a legitimate login.
        $credentials = [
            'email' => strtolower(trim($request->input('email'))),
            'password' => (string) $request->input('password'),
        ];

        $guard = Auth::guard('web');

        if (!$guard->attempt($credentials, $request->boolean('remember'))) {
            return $failure();
        }

        $request->session()->regenerate();
        $user = $guard->user();

        // Banned accounts are terminated immediately, even if credentials were valid.
        if ((int) ($user->is_banned ?? 0) === 1) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->with('error', 'This account has been suspended. Contact support for assistance.');
        }

        // Unverified accounts may still sign in, but are nudged (see the banner in the layout).
        if ($user->email_verified_at === null) {
            session()->flash('status', 'Please verify your email address — check your inbox for the verification link.');
        }

        // Check user role and redirect accordingly
        if ($user->role == 0) {
            return redirect()->intended(route('user.dashboard'));
        } elseif ($user->role == 1) {
            return redirect()->intended(route('admin-dashboard'));
        } elseif ($user->role == 2) {
            return redirect()->intended(route('company.dashboard'));
        }

        return redirect()->route('home');
    }

    public function signOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Signed out successfully!');
    }


    public function forgotPassword(Request $request)
    {
        if (!AuthMethodSettings::emailEnabled()) {
            return redirect()->route('password.request')->with('error', 'Password reset is unavailable while email sign-in is disabled.');
        }

        $request->validate([
            'email' => ['required', 'email:filter', 'max:255'],
        ]);

        $email = strtolower(trim($request->input('email')));
        try {
            Password::sendResetLink(['email' => $email]);
        } catch (\Throwable $e) {
            report($e);
        }

        // Identical response either way: prevents account enumeration.
        return redirect()->route('password.request')->with('status', 'If that email address exists in our records, a password reset link has been sent.');
    }
}

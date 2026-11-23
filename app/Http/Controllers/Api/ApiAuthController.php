<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QJobsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Services\AuthMethodSettings;

class ApiAuthController extends Controller
{
    /** Request a reset link without revealing whether the email is registered. */
    public function forgotPassword(Request $request)
    {
        if (!AuthMethodSettings::emailEnabled()) {
            return response()->json(['success' => false, 'message' => 'Email sign-in is disabled.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email:filter|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            Password::sendResetLink(['email' => strtolower(trim($request->input('email')))]);
        } catch (\Throwable $e) {
            // Keep the response generic even if mail delivery is unavailable.
            report($e);
        }

        return response()->json([
            'success' => true,
            'message' => 'If an account uses that email, a password reset link has been sent.',
        ]);
    }

    /** Complete a reset using the one-time token from the reset email. */
    public function resetPassword(Request $request)
    {
        if (!AuthMethodSettings::emailEnabled()) {
            return response()->json(['success' => false, 'message' => 'Email sign-in is disabled.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email:filter|max:255',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'password.regex' => 'The password must contain upper and lower case letters and a number.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $status = Password::reset([
            'email' => strtolower(trim($request->input('email'))),
            'token' => $request->input('token'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
        ], function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['success' => true, 'message' => 'Your password has been reset.']);
        }

        return response()->json([
            'success' => false,
            'message' => trans($status),
        ], 422);
    }

    /**
     * Login via API — returns user data + role on success.
     * Uses session auth (same as web) so all existing middleware works.
     */
    public function login(Request $request)
    {
        if (!AuthMethodSettings::emailEnabled()) {
            return response()->json(['success' => false, 'message' => 'Email sign-in is disabled.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            if ((int) $user->role !== 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'success' => false,
                    'message' => 'Company and admin accounts must sign in on the QJobs website. The Android app is for job seekers.',
                ], 403);
            }

            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'       => $user->id,
                    'name'     => $user->name,
                    'email'    => $user->email,
                    'username' => $user->username,
                    'role'     => $user->role,
                    'image'    => $user->image,
                    'cover'    => $user->cover,
                    'slug'     => $user->slug,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.',
        ], 401);
    }

    /**
     * Register via API — creates job-seeker accounts only.
     * account and logs the new user in. No verification email is sent from
     * the API path (the account is immediately usable; email verification
     * can still be completed later from the web app).
     */
    public function register(Request $request)
    {
        if (!AuthMethodSettings::emailEnabled()) {
            return response()->json(['success' => false, 'message' => 'Email registration is disabled.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'username'     => [
                'nullable', 'string', 'min:3', 'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/',
                'unique:qjobs_users,username',
            ],
            'birthday'     => 'required|date|before:today',
            'gender'       => 'required|in:0,1',
            'country_id'   => 'required|integer|exists:qjobs_countries,id',
            'email'        => 'required|email:filter|unique:qjobs_users,email|max:255',
            'password'     => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'role'         => 'sometimes|in:0',
        ], [
            'username.regex' => 'Username may only contain letters, numbers, dots, dashes and underscores.',
            'password.regex' => 'The password must contain upper and lower case letters and a number.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $email    = strtolower(trim($request->input('email')));
        $username = $request->filled('username') ? strtolower(trim($request->input('username'))) : null;

        $user = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $email, $username) {
            $user = new QJobsUser();
            $user->name         = trim($request->input('name'));
            $user->username     = $username;
            $user->birthday     = $request->input('birthday');
            $user->gender       = $request->input('gender') ?? 0;
            $user->country_id   = $request->input('country_id');
            $user->email        = $email;
            $user->password     = Hash::make($request->input('password'));
            $user->referral_code = Str::random(8);
            $user->role         = 0;
            $user->save();

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully.',
            'data'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'username' => $user->username,
                'role'     => $user->role,
                'image'    => $user->image,
                'cover'    => $user->cover,
                'slug'     => $user->slug,
            ],
        ], 201);
    }

    /**
     * Logout via API.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /** Public login methods shared by the web sign-in modal and Android app. */
    public function options()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'email_enabled' => AuthMethodSettings::emailEnabled(),
                'google_enabled' => AuthMethodSettings::googleEnabled(),
            ],
        ]);
    }

    /** Exchange a short-lived, one-use Google callback code for an Android session. */
    public function googleExchange(Request $request)
    {
        $request->validate(['code' => 'required|string|min:32|max:128']);
        $cacheKey = 'qjobs:google:exchange:' . hash('sha256', $request->input('code'));
        $userId = Cache::pull($cacheKey);
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Google sign-in code expired. Please try again.'], 401);
        }

        $user = QJobsUser::find($userId);
        if (!$user || (int) ($user->is_banned ?? 0) === 1) {
            return response()->json(['success' => false, 'message' => 'This account cannot sign in.'], 403);
        }

        if ((int) $user->role !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Company and admin accounts must sign in on the QJobs website. The Android app is for job seekers.',
            ], 403);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
                'image' => $user->image,
                'cover' => $user->cover,
                'slug' => $user->slug,
            ],
        ]);
    }

    /**
     * Get current authenticated user.
     */
    public function me(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated.',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'username' => $user->username,
                'role'     => $user->role,
                'image'    => $user->image,
                'cover'    => $user->cover,
                'slug'     => $user->slug,
            ],
        ]);
    }
}

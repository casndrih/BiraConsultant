<?php

namespace App\Http\Controllers\Auth;

use App\Mail\VerifyEmail;
use App\Models\BiraConsultantUser;
use App\Models\BiraConsultantCountry;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Mail;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Services\AuthMethodSettings;

class RegistrationController extends Controller {

    public function showRegistrationForm()
    {
        if (!AuthMethodSettings::emailEnabled()) {
            return redirect()->route('login')->with('error', 'Email registration is currently disabled.');
        }

        $countries = BiraConsultantCountry::orderBy('name')->get(['id', 'name']);
        return view('auth.register', [
            'countries' => $countries,
            'emailRegistrationEnabled' => AuthMethodSettings::emailEnabled(),
        ]);
    }

    public function signup(Request $request) {
        if (!AuthMethodSettings::emailEnabled()) {
            return back()->with('error', 'Email registration is disabled. Choose another enabled sign-in method.');
        }
        // Validate the user input.
        // NOTE: role is restricted to 0 (applicant) and 2 (company) — admin (1)
        // can never be self-assigned. Passwords require upper+lower case and a digit.
        $validator = Validator::make($request->all(), [
           'name' => ['required', 'string', 'max:255'],
           'username' => [
                'nullable', 'string', 'min:3', 'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/',
                'unique:biraconsultant_users,username',
            ],
           'birthday' => ['required_if:role,0', 'nullable', 'date', 'before:today'],
           'gender' => ['required_if:role,0', 'nullable', 'in:0,1'],
           'country_id' => ['required_if:role,0', 'nullable', 'integer', 'exists:biraconsultant_countries,id'],
           'email' => ['required', 'string', 'email:filter', 'unique:biraconsultant_users,email', 'max:255'],
           'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()],
           'role' => ['required', 'in:0,2'],
           'company_name' => ['required_if:role,2', 'nullable', 'string', 'max:255'],
        ], [
            'username.regex' => 'Username may only contain letters, numbers, dots, dashes and underscores.',
            'password' => 'The password must be at least 8 characters and contain upper and lower case letters and a number.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Normalize identity fields (prevents case-variant duplicate accounts).
        $email = strtolower(trim($request->input('email')));
        $username = $request->filled('username')
            ? strtolower(trim($request->input('username')))
            : null;

        // Generate a unique referral code (retry on the rare unique-index collision).
        $referralCode = Str::random(8);
        for ($i = 0; $i < 5 && BiraConsultantUser::where('referral_code', $referralCode)->exists(); $i++) {
            $referralCode = Str::random(8);
        }

        // Create the new user (transactional so a failure never leaves a partial row).
        $user = DB::transaction(function () use ($request, $email, $username, $referralCode) {
            $user = new BiraConsultantUser();
            $user->name = trim($request->input('name'));
            $user->username = $username;
            $user->birthday = $request->input('birthday');
            // gender column is NOT NULL - company signups submit no gender, default to 0.
            $user->gender = $request->input('gender') ?? 0;
            $user->country_id = $request->input('country_id');
            $user->email = $email;
            $user->password = Hash::make($request->input('password'));
            $user->email_verification_token = Str::random(32);
            $user->referral_code = $referralCode;
            $user->role = (int) $request->input('role', 0);
            $user->save();

            return $user;
        });

        // If company role, create an associated company profile.
        $company = null;
        if ($user->role === 2 && $request->filled('company_name')) {
            $company = \App\Models\BiraConsultantCompany::create([
                'name' => trim($request->input('company_name')),
                'email' => $user->email,
                'user_id' => $user->id,
                'is_approved' => 0,
            ]);
        }

        try {
            Mail::to($user->email)->send(new VerifyEmail($user, $user->email_verification_token));
        } catch (\Throwable $e) {
            // Mail failure: roll back the account (and company) so no half-verified
            // account is left behind, then tell the user what happened.
            report($e);
            if ($company) {
                $company->delete();
            }
            $user->delete();

            return redirect()->route('register')->withInput($request->except(['password', 'password_confirmation']))->with('error', 'We could not send the verification email. Please try again in a moment.');
        }

        Auth::login($user);
        // Session fixation protection: new id for the newly authenticated session.
        $request->session()->regenerate();

        // Redirect based on role
        if ($user->role === 2) {
            return redirect()->route('company.dashboard')->with('status', 'Account created! Please verify your email address.');
        }
        return redirect()->route('home')->with('status', 'Account created! Please verify your email address.');
    }

    public function verifyEmail($token) {
        $user = BiraConsultantUser::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect('/')->with('error', 'Invalid verification token.');
        }

        if ($user->email_verified_at !== null) {
            return redirect('/')->with('error', 'Your email has already been verified.');
        }

        $user->email_verified_at = Carbon::now();
        $user->email_verification_token = null;
        $user->save();

        return redirect()->route('home')->with('success', 'Your email has been successfully verified.');
    }

    public function checkEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email:filter', 'max:255']]);

        $exists = BiraConsultantUser::where('email', strtolower(trim($request->input('email'))))->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkUsername(Request $request)
    {
        $request->validate(['username' => ['required', 'string', 'min:3', 'max:50']]);

        $exists = BiraConsultantUser::where('username', strtolower(trim($request->input('username'))))->exists();

        return response()->json(['exists' => $exists]);
    }

    public function showResetForm(Request $request, $token = null) {
        return view('auth.passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('home')->with('status', 'Password reset successfully');
        } else {
            return back()->withErrors(['email' => [trans($response)]]);
        }
    }

    // API endpoint to fetch countries for signup form
    public function getCountries()
    {
        $countries = BiraConsultantCountry::orderBy('name')->get();
        return response()->json($countries);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantUser;
use App\Services\AuthMethodSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        abort_unless(AuthMethodSettings::googleEnabled(), 404);

        $state = Str::random(48);
        $request->session()->put('biraconsultant.google.state_hash', hash('sha256', $state));
        $request->session()->put('biraconsultant.google.platform', $request->query('platform') === 'android' ? 'android' : 'web');

        $query = http_build_query([
            'client_id' => AuthMethodSettings::googleClientId(),
            'redirect_uri' => AuthMethodSettings::googleRedirectUri(),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
        ], '', '&', PHP_QUERY_RFC3986);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function callback(Request $request)
    {
        $expectedHash = $request->session()->pull('biraconsultant.google.state_hash');
        $platform = $request->session()->pull('biraconsultant.google.platform', 'web');
        $state = (string) $request->query('state', '');

        if (!$expectedHash || $state === '' || !hash_equals($expectedHash, hash('sha256', $state))) {
            return $this->failed($platform, 'invalid_state');
        }
        if ($request->filled('error') || !$request->filled('code')) {
            return $this->failed($platform, 'cancelled');
        }
        if (!AuthMethodSettings::googleEnabled()) {
            return $this->failed($platform, 'disabled');
        }

        try {
            $tokenResponse = Http::asForm()->timeout(20)->post('https://oauth2.googleapis.com/token', [
                'code' => $request->query('code'),
                'client_id' => AuthMethodSettings::googleClientId(),
                'client_secret' => AuthMethodSettings::googleClientSecret(),
                'redirect_uri' => AuthMethodSettings::googleRedirectUri(),
                'grant_type' => 'authorization_code',
            ]);

            if (!$tokenResponse->successful() || !$tokenResponse->json('access_token')) {
                return $this->failed($platform, 'token_exchange_failed');
            }

            $identityResponse = Http::withToken($tokenResponse->json('access_token'))
                ->timeout(20)
                ->get('https://openidconnect.googleapis.com/v1/userinfo');

            if (!$identityResponse->successful()) {
                return $this->failed($platform, 'identity_check_failed');
            }

            $identity = $identityResponse->json();
            $email = strtolower(trim((string) ($identity['email'] ?? '')));
            $googleId = trim((string) ($identity['sub'] ?? ''));
            $verified = filter_var($identity['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);
            if (!$verified || $googleId === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->failed($platform, 'unverified_identity');
            }

            $user = BiraConsultantUser::where('google_id', $googleId)->first();
            if (!$user) {
                $user = BiraConsultantUser::whereRaw('LOWER(email) = ?', [$email])->first();
                if ($user) {
                    $user->google_id = $googleId;
                    $user->email_verified_at ??= now();
                    $user->save();
                } else {
                    $user = new BiraConsultantUser();
                    $user->name = trim((string) ($identity['name'] ?? '')) ?: strstr($email, '@', true);
                    $user->email = $email;
                    $user->google_id = $googleId;
                    $user->password = Hash::make(Str::random(64));
                    $user->role = 0;
                    $user->gender = 0;
                    $user->email_verified_at = now();
                    $user->referral_code = Str::random(8);
                    $user->image = $identity['picture'] ?? null;
                    $user->save();
                }
            }

            if ((int) ($user->is_banned ?? 0) === 1) {
                return $this->failed($platform, 'account_suspended');
            }

            if ($platform === 'android') {
                $code = Str::random(64);
                Cache::put('biraconsultant:google:exchange:' . hash('sha256', $code), $user->id, now()->addMinutes(2));
                return redirect()->away('biraconsultant://auth/google?code=' . urlencode($code));
            }

            Auth::login($user);
            $request->session()->regenerate();
            return match ((int) $user->role) {
                0 => redirect()->intended(route('user.dashboard')),
                1 => redirect()->intended(route('admin-dashboard')),
                2 => redirect()->intended(route('company.dashboard')),
                default => redirect()->route('home'),
            };
        } catch (\Throwable $exception) {
            report($exception);
            return $this->failed($platform, 'google_sign_in_failed');
        }
    }

    private function failed(string $platform, string $reason)
    {
        if ($platform === 'android') {
            return redirect()->away('biraconsultant://auth/google?error=' . urlencode($reason));
        }

        return redirect()->route('home')->with('error', 'Google sign-in could not be completed. Please try again.');
    }
}

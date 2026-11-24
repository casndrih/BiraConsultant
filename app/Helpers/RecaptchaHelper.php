<?php

namespace App\Helpers;

use App\Models\BiraConsultantSetting;
use Illuminate\Support\Facades\Http;

class RecaptchaHelper
{
    /**
     * Check if reCAPTCHA is enabled in admin settings.
     */
    public static function isEnabled(): bool
    {
        return BiraConsultantSetting::getValue('recaptcha_enabled', '0') === '1';
    }

    /**
     * Get the reCAPTCHA site key.
     */
    public static function getSiteKey(): string
    {
        return BiraConsultantSetting::getValue('recaptcha_site_key', '');
    }

    /**
     * Get the reCAPTCHA secret key.
     */
    public static function getSecretKey(): string
    {
        return BiraConsultantSetting::getValue('recaptcha_secret_key', '');
    }

    /**
     * Verify the reCAPTCHA response token with Google.
     */
    public static function verify(string $token): bool
    {
        if (!self::isEnabled()) {
            return true;
        }

        $secretKey = self::getSecretKey();

        if (empty($secretKey)) {
            return true;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $token,
            ]);

            $data = $response->json();

            return isset($data['success']) && $data['success'] === true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
            return false;
        }
    }
}

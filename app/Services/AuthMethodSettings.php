<?php

namespace App\Services;

use App\Models\QJobsSetting;

class AuthMethodSettings
{
    public static function emailEnabled(): bool
    {
        $value = trim(QJobsSetting::getValue('email_login_enabled', '1'));
        return $value === '' || filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function googleClientId(): string
    {
        return trim(QJobsSetting::getValue('google_client_id'));
    }

    public static function googleClientSecret(): string
    {
        return trim(QJobsSetting::getValue('google_client_secret'));
    }

    public static function googleRedirectUri(): string
    {
        return trim(QJobsSetting::getValue('google_redirect_uri')) ?: url('/auth/google/callback');
    }

    public static function googleEnabled(): bool
    {
        return filter_var(QJobsSetting::getValue('google_login_enabled', '0'), FILTER_VALIDATE_BOOLEAN)
            && self::googleClientId() !== ''
            && self::googleClientSecret() !== '';
    }
}

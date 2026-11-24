<?php

namespace App\Services;

use App\Models\BiraConsultantSetting;

class AuthMethodSettings
{
    public static function emailEnabled(): bool
    {
        $value = trim(BiraConsultantSetting::getValue('email_login_enabled', '1'));
        return $value === '' || filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function googleClientId(): string
    {
        return trim(BiraConsultantSetting::getValue('google_client_id'));
    }

    public static function googleClientSecret(): string
    {
        return trim(BiraConsultantSetting::getValue('google_client_secret'));
    }

    public static function googleRedirectUri(): string
    {
        return trim(BiraConsultantSetting::getValue('google_redirect_uri')) ?: url('/auth/google/callback');
    }

    public static function googleEnabled(): bool
    {
        return filter_var(BiraConsultantSetting::getValue('google_login_enabled', '0'), FILTER_VALIDATE_BOOLEAN)
            && self::googleClientId() !== ''
            && self::googleClientSecret() !== '';
    }
}

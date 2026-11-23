<?php

namespace App\Helpers;

use App\Models\QJobsSetting;

class UploadLimitHelper
{
    /**
     * Default upload limits in KB (2MB = 2048 KB).
     */
    private const DEFAULTS = [
        'profile_image'     => 2048,
        'profile_cover_image' => 5120,
        'company_logo'      => 2048,
        'company_docs'      => 2048,
        'application_files' => 2048,
        'blog_images'       => 2048,
        'nid_docs'          => 2048,
        'admin_user_image'  => 2048,
        'admin_user_cover'  => 5120,
    ];

    /**
     * Setting key map — admin setting key => our internal key.
     */
    private const SETTING_KEYS = [
        'upload_limit_profile_image'     => 'profile_image',
        'upload_limit_profile_cover_image' => 'profile_cover_image',
        'upload_limit_company_logo'      => 'company_logo',
        'upload_limit_company_docs'      => 'company_docs',
        'upload_limit_application_files' => 'application_files',
        'upload_limit_blog_images'       => 'blog_images',
        'upload_limit_nid_docs'          => 'nid_docs',
        'upload_limit_admin_user_image'  => 'admin_user_image',
        'upload_limit_admin_user_cover'  => 'admin_user_cover',
    ];

    /**
     * Get upload limit (in KB) for a given type.
     */
    public static function get(string $type): int
    {
        $settingKey = array_search($type, self::SETTING_KEYS);
        if ($settingKey === false) {
            return self::DEFAULTS[$type] ?? 2048;
        }

        $value = QJobsSetting::getValue($settingKey);
        if ($value === null || $value === '' || (int) $value <= 0) {
            return self::DEFAULTS[$type] ?? 2048;
        }

        return (int) $value;
    }

    /**
     * Get all upload limits as [type => kb].
     */
    public static function all(): array
    {
        $limits = [];
        foreach (self::DEFAULTS as $type => $default) {
            $limits[$type] = self::get($type);
        }
        return $limits;
    }

    /**
     * Get the max rule value for Laravel validation.
     * Returns the limit in KB for use in 'max:X' rule.
     */
    public static function maxRule(string $type): int
    {
        return self::get($type);
    }
}

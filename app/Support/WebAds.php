<?php

namespace App\Support;

use App\Models\BiraConsultantSetting;
use Throwable;

final class WebAds
{
    private const PLACEMENTS = [
        'home', 'jobs', 'job_detail', 'companies', 'company_detail', 'blog', 'blog_detail',
    ];

    private static ?array $settings = null;

    private static function settings(): array
    {
        if (self::$settings !== null) {
            return self::$settings;
        }

        $keys = ['website_ads_enabled', 'website_ads_test_mode', 'adsense_publisher_id'];
        foreach (self::PLACEMENTS as $placement) {
            $keys[] = 'adsense_slot_' . $placement;
        }

        try {
            self::$settings = BiraConsultantSetting::whereIn('key', $keys)->pluck('value', 'key')->all();
        } catch (Throwable) {
            self::$settings = [];
        }

        return self::$settings;
    }

    public static function enabled(): bool
    {
        return filter_var(self::settings()['website_ads_enabled'] ?? '1', FILTER_VALIDATE_BOOLEAN);
    }

    public static function publisherId(): string
    {
        $publisherId = trim((string) (self::settings()['adsense_publisher_id'] ?? ''));
        return preg_match('/^ca-pub-\d{16}$/', $publisherId) ? $publisherId : '';
    }

    public static function testMode(): bool
    {
        return app()->environment('local', 'testing')
            && filter_var(self::settings()['website_ads_test_mode'] ?? '1', FILTER_VALIDATE_BOOLEAN);
    }

    public static function liveAdsAllowed(): bool
    {
        return !app()->environment('local', 'testing');
    }

    public static function placement(string $name): array
    {
        $settings = self::settings();
        $slot = in_array($name, self::PLACEMENTS, true)
            ? trim((string) ($settings['adsense_slot_' . $name] ?? ''))
            : '';
        if (!preg_match('/^\d{4,20}$/', $slot)) {
            $slot = '';
        }

        $publisherId = self::publisherId();
        $testMode = self::testMode();
        return [
            'enabled' => self::enabled(),
            'test_mode' => $testMode,
            'test_id' => 'biraconsultant-web-test-' . $name . '-v1',
            'publisher_id' => $publisherId,
            'slot_id' => $slot,
            'has_ad_unit' => $publisherId !== '' && $slot !== '' && self::liveAdsAllowed(),
        ];
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\QJobsSetting;
use Illuminate\Http\Request;

class AdminSettingApiController extends Controller
{
    private const SETTINGS_GROUPS = [
        'general' => ['site_name', 'site_description', 'site_logo', 'default_language', 'timezone', 'date_format'],
        'company' => ['default_company_logo', 'max_companies_per_user', 'company_approval_required', 'company_categories'],
        'email' => ['mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'],
        'sms' => ['sms_driver', 'sms_api_key', 'sms_from_number', 'sms_enabled'],
        'jobs' => ['jobs_per_page', 'job_approval_required', 'job_expiry_days', 'featured_job_price'],
        'advanced' => ['enable_registration', 'enable_blog', 'enable_company_registration', 'maintenance_mode', 'recaptcha_enabled', 'recaptcha_site_key', 'recaptcha_secret_key'],
        'verification' => ['email_verification_required', 'phone_verification_enabled', 'identity_verification_required', 'identity_verification_enabled', 'company_verification_required'],
        'authentication' => ['email_login_enabled', 'google_login_enabled', 'google_client_id', 'google_client_secret', 'google_redirect_uri'],
        'ads' => [
            'mobile_ads_provider', 'google_ads_enabled', 'google_banner_enabled', 'google_native_enabled',
            'google_ads_test_mode', 'google_banner_ad_unit_id', 'google_native_ad_unit_id',
            'ironsource_ads_enabled', 'ironsource_banner_enabled', 'ironsource_app_key',
            'ironsource_banner_ad_unit_id', 'ironsource_banner_placement',
            'tapjoy_ads_enabled', 'tapjoy_offerwall_enabled', 'tapjoy_sdk_key', 'tapjoy_offerwall_placement',
            'website_ads_enabled', 'website_ads_test_mode', 'adsense_publisher_id',
            'adsense_slot_home', 'adsense_slot_jobs', 'adsense_slot_job_detail',
            'adsense_slot_companies', 'adsense_slot_company_detail',
            'adsense_slot_blog', 'adsense_slot_blog_detail',
        ],
        'uploads' => ['upload_limit_profile_image', 'upload_limit_profile_cover_image', 'upload_limit_company_logo', 'upload_limit_company_docs', 'upload_limit_application_files', 'upload_limit_blog_images', 'upload_limit_nid_docs', 'upload_limit_admin_user_image', 'upload_limit_admin_user_cover'],
    ];

    public function index()
    {
        $settings = [];
        foreach (self::SETTINGS_GROUPS as $group => $keys) {
            $settings[$group] = QJobsSetting::whereIn('key', $keys)->get()->keyBy('key');
            foreach ($keys as $key) {
                if (!isset($settings[$group][$key])) {
                    $settings[$group][$key] = ['key' => $key, 'value' => ''];
                }
            }
        }
        $settings['authentication']['google_client_secret'] = [
            'key' => 'google_client_secret',
            'value' => '',
            'configured' => \App\Services\AuthMethodSettings::googleClientSecret() !== '',
        ];
        return response()->json(['success' => true, 'data' => $settings]);
    }

    public function show($key)
    {
        $setting = QJobsSetting::where('key', $key)->first();
        if ($key === 'google_client_secret') {
            return response()->json(['success' => true, 'data' => [
                'key' => $key,
                'value' => '',
                'configured' => \App\Services\AuthMethodSettings::googleClientSecret() !== '',
            ]]);
        }
        return response()->json(['success' => true, 'data' => $setting]);
    }

    public function update(Request $request)
    {
        $input = $request->input('settings', []);
        if (!is_array($input)) {
            return response()->json(['success' => false, 'message' => 'Settings must be an object'], 422);
        }

        $allowedKeys = collect(self::SETTINGS_GROUPS)->flatten()->all();
        $unknownKeys = array_diff(array_keys($input), $allowedKeys);
        if ($unknownKeys) {
            return response()->json(['success' => false, 'message' => 'One or more settings are not supported'], 422);
        }

        $rules = [];
        foreach ($input as $key => $_value) {
            $rules["settings.{$key}"] = 'nullable|string|max:1000';
        }
        if (array_key_exists('mobile_ads_provider', $input)) {
            $rules['settings.mobile_ads_provider'] = 'required|in:google,ironsource,tapjoy,none';
        }
        if (array_key_exists('website_ads_enabled', $input)) {
            $rules['settings.website_ads_enabled'] = 'required|in:0,1';
        }
        if (array_key_exists('website_ads_test_mode', $input)) {
            $rules['settings.website_ads_test_mode'] = 'required|in:0,1';
        }
        if (array_key_exists('adsense_publisher_id', $input)) {
            $rules['settings.adsense_publisher_id'] = ['nullable', 'regex:/^ca-pub-\d{16}$/'];
        }
        foreach ([
            'adsense_slot_home', 'adsense_slot_jobs', 'adsense_slot_job_detail',
            'adsense_slot_companies', 'adsense_slot_company_detail',
            'adsense_slot_blog', 'adsense_slot_blog_detail',
        ] as $slotKey) {
            if (array_key_exists($slotKey, $input)) {
                $rules["settings.{$slotKey}"] = ['nullable', 'regex:/^\d{4,20}$/'];
            }
        }

        $data = $request->validate($rules)['settings'] ?? [];
        if (array_key_exists('mobile_ads_provider', $data)) {
            $provider = $data['mobile_ads_provider'];
            $data['google_ads_enabled'] = $provider === 'google' ? '1' : '0';
            $data['ironsource_ads_enabled'] = $provider === 'ironsource' ? '1' : '0';
            $data['tapjoy_ads_enabled'] = $provider === 'tapjoy' ? '1' : '0';
        }

        foreach ($data as $key => $value) {
            if ($key === 'google_client_secret' && trim((string) $value) === '') {
                continue;
            }
            QJobsSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return response()->json(['success' => true, 'message' => 'Settings updated']);
    }
}

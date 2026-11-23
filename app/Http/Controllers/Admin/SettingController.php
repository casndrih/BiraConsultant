<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QJobsSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Grouped list of all setting keys by section.
     */
    private const SETTINGS_GROUPS = [
        'general' => [
            'site_name', 'site_description', 'site_logo', 'default_language',
            'timezone', 'date_format',
        ],
        'company' => [
            'default_company_logo', 'max_companies_per_user',
            'company_approval_required', 'company_categories',
        ],
        'email' => [
            'mail_driver', 'mail_host', 'mail_port', 'mail_username',
            'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
        ],
        'sms' => [
            'sms_driver', 'sms_api_key', 'sms_from_number', 'sms_enabled',
        ],
        'jobs' => [
            'jobs_per_page', 'job_approval_required', 'job_expiry_days',
            'featured_job_price', 'job_categories_enabled', 'job_types_enabled',
            'experience_levels_enabled', 'skills_enabled',
        ],
        'advanced' => [
            'enable_registration', 'enable_blog', 'enable_company_registration',
            'maintenance_mode', 'custom_css', 'custom_js',
            'google_maps_api_key', 'google_maps_enabled',
            'recaptcha_enabled', 'recaptcha_site_key', 'recaptcha_secret_key',
        ],
        'verification' => [
            'email_verification_required', 'phone_verification_enabled',
            'phone_verification_required', 'identity_verification_required',
            'identity_verification_enabled', 'company_verification_required',
            'auto_approve_companies', 'auto_approve_users',
            'verification_sms_driver', 'sms_verification_template',
        ],
        'authentication' => [
            'email_login_enabled', 'google_login_enabled',
            'google_client_id', 'google_client_secret', 'google_redirect_uri',
        ],
        'ads' => [
            'mobile_ads_provider',
            'google_ads_enabled', 'google_banner_enabled', 'google_native_enabled',
            'google_ads_test_mode', 'google_banner_ad_unit_id', 'google_native_ad_unit_id',
            'ironsource_ads_enabled', 'ironsource_banner_enabled', 'ironsource_app_key',
            'ironsource_banner_ad_unit_id', 'ironsource_banner_placement',
            'tapjoy_ads_enabled', 'tapjoy_offerwall_enabled', 'tapjoy_sdk_key', 'tapjoy_offerwall_placement',
            'website_ads_enabled', 'website_ads_test_mode', 'adsense_publisher_id',
            'adsense_slot_home', 'adsense_slot_jobs', 'adsense_slot_job_detail',
            'adsense_slot_companies', 'adsense_slot_company_detail',
            'adsense_slot_blog', 'adsense_slot_blog_detail',
        ],
        'legal' => [
            'privacy_policy', 'terms_of_service',
        ],
        'uploads' => [
            'upload_limit_profile_image',
            'upload_limit_profile_cover_image',
            'upload_limit_company_logo',
            'upload_limit_company_docs',
            'upload_limit_application_files',
            'upload_limit_blog_images',
            'upload_limit_nid_docs',
            'upload_limit_admin_user_image',
            'upload_limit_admin_user_cover',
        ],
    ];

    /**
     * Display all settings grouped by section.
     */
    public function index()
    {
        $settings = [];

        foreach (self::SETTINGS_GROUPS as $group => $keys) {
            $settings[$group] = QJobsSetting::whereIn('key', $keys)->get()->keyBy('key');

            // Fill missing keys with empty QJobsSetting instances
            foreach ($keys as $key) {
                if (!isset($settings[$group][$key])) {
                    $settings[$group][$key] = new QJobsSetting(['key' => $key, 'value' => '']);
                }
            }
        }

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Search settings by key or value and return filtered view.
     */
    public function query(Request $request)
    {
        $query = $request->input('query', '');

        $filtered = QJobsSetting::when($query, function ($q) use ($query) {
            $q->where('key', 'like', "%{$query}%")
              ->orWhere('value', 'like', "%{$query}%");
        })->get()->keyBy('key');

        $settings = [];

        foreach (self::SETTINGS_GROUPS as $group => $keys) {
            $settings[$group] = collect();
            foreach ($keys as $key) {
                $setting = $filtered->get($key, null);
                if ($setting) {
                    $settings[$group]->put($key, $setting);
                }
            }
        }

        return view('admin.settings.index', compact('settings', 'query'));
    }

    /**
     * Update or create settings in bulk.
     */
    public function update(Request $request)
    {
        $data = $request->input('settings', []);

        $rules = [];
        $messages = [];
        if (array_key_exists('mobile_ads_provider', $data)) {
            $rules['settings.mobile_ads_provider'] = 'required|in:google,ironsource,tapjoy,none';
        }
        foreach ($data as $key => $value) {
            $rules["settings.{$key}"] = 'nullable|string|max:1000';
        }
        if (array_key_exists('website_ads_enabled', $data)) {
            $rules['settings.website_ads_enabled'] = 'required|in:0,1';
        }
        if (array_key_exists('website_ads_test_mode', $data)) {
            $rules['settings.website_ads_test_mode'] = 'required|in:0,1';
        }
        if (array_key_exists('adsense_publisher_id', $data)) {
            $rules['settings.adsense_publisher_id'] = ['nullable', 'regex:/^ca-pub-\d{16}$/'];
        }
        foreach ([
            'adsense_slot_home', 'adsense_slot_jobs', 'adsense_slot_job_detail',
            'adsense_slot_companies', 'adsense_slot_company_detail',
            'adsense_slot_blog', 'adsense_slot_blog_detail',
        ] as $slotKey) {
            if (array_key_exists($slotKey, $data)) {
                $rules["settings.{$slotKey}"] = ['nullable', 'regex:/^\d{4,20}$/'];
            }
        }

        $validated = $request->validate($rules, $messages);

        if (isset($data['mobile_ads_provider'])) {
            $activeProvider = $data['mobile_ads_provider'];
            $data['google_ads_enabled'] = $activeProvider === 'google' ? '1' : '0';
            $data['ironsource_ads_enabled'] = $activeProvider === 'ironsource' ? '1' : '0';
            $data['tapjoy_ads_enabled'] = $activeProvider === 'tapjoy' ? '1' : '0';
        }

        $updatedCount = 0;

        foreach ($data as $key => $value) {
            if ($key === 'google_client_secret' && trim((string) $value) === '') {
                continue; // Empty form field means keep the currently stored secret.
            }
            QJobsSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
            $updatedCount++;
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', "Settings updated successfully. ({$updatedCount} setting(s) saved)");
    }
}

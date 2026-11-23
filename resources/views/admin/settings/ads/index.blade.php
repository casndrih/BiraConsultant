@php
    $adsSettings = $settings['ads'] ?? collect();
    $adsValue = fn ($key, $default = '') => (($adsSettings[$key]->value ?? '') !== '')
        ? $adsSettings[$key]->value
        : $default;
    $activeAdsProvider = $adsValue('mobile_ads_provider', '');
    if (!in_array($activeAdsProvider, ['google', 'ironsource', 'tapjoy', 'none'], true)) {
        $activeAdsProvider = filter_var($adsValue('google_ads_enabled', '1'), FILTER_VALIDATE_BOOLEAN)
            ? 'google'
            : (filter_var($adsValue('ironsource_ads_enabled', '0'), FILTER_VALIDATE_BOOLEAN)
                ? 'ironsource'
                : (filter_var($adsValue('tapjoy_ads_enabled', '0'), FILTER_VALIDATE_BOOLEAN) ? 'tapjoy' : 'none'));
    }
@endphp

<div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mobile Ad Providers</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Choose one active ad provider. The app will never request ads from multiple providers at once.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="mobile_ads_provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Active provider</label>
            <select id="mobile_ads_provider" name="settings[mobile_ads_provider]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                <option value="google" @selected($activeAdsProvider === 'google')>Google AdMob</option>
                <option value="ironsource" @selected($activeAdsProvider === 'ironsource')>ironSource LevelPlay</option>
                <option value="tapjoy" @selected($activeAdsProvider === 'tapjoy')>Tapjoy Offerwall</option>
                <option value="none" @selected($activeAdsProvider === 'none')>Disabled</option>
            </select>
        </div>

        <div>
            <label for="google_ads_test_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Use Google test ads</label>
            <select id="google_ads_test_mode" name="settings[google_ads_test_mode]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                <option value="1" @selected(filter_var($adsValue('google_ads_test_mode', '1'), FILTER_VALIDATE_BOOLEAN))>Yes (safe test IDs)</option>
                <option value="0" @selected(!filter_var($adsValue('google_ads_test_mode', '1'), FILTER_VALIDATE_BOOLEAN))>No (use live IDs below)</option>
            </select>
        </div>

        <div>
            <label for="google_banner_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Banner ads</label>
            <select id="google_banner_enabled" name="settings[google_banner_enabled]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                <option value="1" @selected(filter_var($adsValue('google_banner_enabled', '1'), FILTER_VALIDATE_BOOLEAN))>Enabled</option>
                <option value="0" @selected(!filter_var($adsValue('google_banner_enabled', '1'), FILTER_VALIDATE_BOOLEAN))>Disabled</option>
            </select>
        </div>

        <div>
            <label for="google_native_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Native ads</label>
            <select id="google_native_enabled" name="settings[google_native_enabled]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                <option value="1" @selected(filter_var($adsValue('google_native_enabled', '1'), FILTER_VALIDATE_BOOLEAN))>Enabled</option>
                <option value="0" @selected(!filter_var($adsValue('google_native_enabled', '1'), FILTER_VALIDATE_BOOLEAN))>Disabled</option>
            </select>
        </div>

        <div>
            <label for="google_banner_ad_unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Live banner ad unit ID</label>
            <input id="google_banner_ad_unit_id" type="text" name="settings[google_banner_ad_unit_id]"
                value="{{ $adsValue('google_banner_ad_unit_id') }}" placeholder="ca-app-pub-…/…"
                autocomplete="off" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
        </div>

        <div>
            <label for="google_native_ad_unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Live native ad unit ID</label>
            <input id="google_native_ad_unit_id" type="text" name="settings[google_native_ad_unit_id]"
                value="{{ $adsValue('google_native_ad_unit_id') }}" placeholder="ca-app-pub-…/…"
                autocomplete="off" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
        </div>
    </div>

    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
        <h4 class="text-base font-semibold text-gray-900 dark:text-white">ironSource LevelPlay</h4>
        <p class="mt-1 mb-5 text-sm text-gray-500 dark:text-gray-400">Set the Android app key and banner ad unit from your LevelPlay dashboard.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="ironsource_app_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Android app key</label>
                <input id="ironsource_app_key" type="text" name="settings[ironsource_app_key]" value="{{ $adsValue('ironsource_app_key') }}" autocomplete="off" placeholder="LevelPlay app key" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
            </div>
            <div>
                <label for="ironsource_banner_ad_unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Banner ad unit ID</label>
                <input id="ironsource_banner_ad_unit_id" type="text" name="settings[ironsource_banner_ad_unit_id]" value="{{ $adsValue('ironsource_banner_ad_unit_id') }}" autocomplete="off" placeholder="LevelPlay banner ad unit ID" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
            </div>
            <div>
                <label for="ironsource_banner_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Banner ads</label>
                <select id="ironsource_banner_enabled" name="settings[ironsource_banner_enabled]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                    <option value="0" @selected(!filter_var($adsValue('ironsource_banner_enabled', '0'), FILTER_VALIDATE_BOOLEAN))>Disabled</option>
                    <option value="1" @selected(filter_var($adsValue('ironsource_banner_enabled', '0'), FILTER_VALIDATE_BOOLEAN))>Enabled</option>
                </select>
            </div>
            <div>
                <label for="ironsource_banner_placement" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Banner placement</label>
                <input id="ironsource_banner_placement" type="text" name="settings[ironsource_banner_placement]" value="{{ $adsValue('ironsource_banner_placement') }}" autocomplete="off" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
            </div>
        </div>
    </div>

    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
        <h4 class="text-base font-semibold text-gray-900 dark:text-white">Tapjoy</h4>
        <p class="mt-1 mb-5 text-sm text-gray-500 dark:text-gray-400">Set the Android SDK key and offerwall placement from your Tapjoy dashboard.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="tapjoy_sdk_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Android SDK key</label>
                <input id="tapjoy_sdk_key" type="text" name="settings[tapjoy_sdk_key]" value="{{ $adsValue('tapjoy_sdk_key') }}" autocomplete="off" placeholder="Tapjoy SDK key" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
            </div>
            @foreach ([
                'tapjoy_offerwall_enabled' => 'Offerwall',
            ] as $key => $label)
                <div>
                    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
                    <select id="{{ $key }}" name="settings[{{ $key }}]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                        <option value="0" @selected(!filter_var($adsValue($key, '0'), FILTER_VALIDATE_BOOLEAN))>Disabled</option>
                        <option value="1" @selected(filter_var($adsValue($key, '0'), FILTER_VALIDATE_BOOLEAN))>Enabled</option>
                    </select>
                </div>
            @endforeach
            @foreach ([
                'tapjoy_offerwall_placement' => 'Offerwall placement',
            ] as $key => $label)
                <div>
                    <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
                    <input id="{{ $key }}" type="text" name="settings[{{ $key }}]" value="{{ $adsValue($key) }}" autocomplete="off" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                </div>
            @endforeach
        </div>
    </div>

    <p class="mt-5 text-xs text-gray-500 dark:text-gray-400">
        Test mode uses Google's demo banner and native IDs. Before going live, register QJobs in AdMob, replace the sample app ID in the Android manifest, enter both live ad unit IDs, then turn test mode off.
    </p>
    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
        Only the provider selected above receives ad requests. LevelPlay requires an app key and banner ad unit ID; Tapjoy requires its SDK key and offerwall placement.
    </p>

    <div class="mt-6 flex justify-end">
        <button type="button" class="save-settings-btn bg-green-400 hover:bg-green-500 text-black font-medium rounded-full px-6 py-2 text-sm">Save Mobile Ads</button>
    </div>
</div>

<div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 mt-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Website Ads</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Show sponsored placements on public QJobs pages. Until an AdSense slot is configured, each enabled placement links visitors to QJobs advertising enquiries.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="website_ads_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website placements</label>
            <select id="website_ads_enabled" name="settings[website_ads_enabled]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                <option value="1" @selected(filter_var($adsValue('website_ads_enabled', '1'), FILTER_VALIDATE_BOOLEAN))>Enabled</option>
                <option value="0" @selected(!filter_var($adsValue('website_ads_enabled', '1'), FILTER_VALIDATE_BOOLEAN))>Disabled</option>
            </select>
        </div>
        <div>
            <label for="website_ads_test_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Local website ad preview</label>
            <select id="website_ads_test_mode" name="settings[website_ads_test_mode]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                <option value="1" @selected(filter_var($adsValue('website_ads_test_mode', app()->environment('local', 'testing') ? '1' : '0'), FILTER_VALIDATE_BOOLEAN))>Enabled in local/testing only</option>
                <option value="0" @selected(!filter_var($adsValue('website_ads_test_mode', app()->environment('local', 'testing') ? '1' : '0'), FILTER_VALIDATE_BOOLEAN))>Disabled</option>
            </select>
        </div>
        <div>
            <label for="adsense_publisher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">AdSense publisher ID</label>
            <input id="adsense_publisher_id" type="text" name="settings[adsense_publisher_id]" value="{{ $adsValue('adsense_publisher_id') }}" autocomplete="off" placeholder="ca-pub-1234567890123456" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
        </div>
        @foreach ([
            'home' => 'Homepage',
            'jobs' => 'Jobs listing',
            'job_detail' => 'Job details',
            'companies' => 'Company listing',
            'company_detail' => 'Company details',
            'blog' => 'Blog listing',
            'blog_detail' => 'Blog articles',
        ] as $placement => $label)
            <div>
                <label for="adsense_slot_{{ $placement }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }} ad slot ID</label>
                <input id="adsense_slot_{{ $placement }}" type="text" inputmode="numeric" name="settings[adsense_slot_{{ $placement }}]" value="{{ $adsValue('adsense_slot_' . $placement) }}" autocomplete="off" placeholder="AdSense slot number" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
            </div>
        @endforeach
    </div>

    <p class="mt-5 text-xs text-gray-500 dark:text-gray-400">Local previews use QJobs test IDs and never contact Google. Live AdSense ads load only outside local/testing when a publisher ID and that placement’s slot ID are configured. Google’s AdMob demo IDs are for mobile apps, not website AdSense slots.</p>
    <div class="mt-6 flex justify-end">
        <button type="button" class="save-settings-btn bg-green-400 hover:bg-green-500 text-black font-medium rounded-full px-6 py-2 text-sm">Save Website Ads</button>
    </div>
</div>

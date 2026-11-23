<div class="bg-white dark:bg-custom-dark shadow-md rounded-lg p-6 mb-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Advanced Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Configure advanced application features and custom code</p>
        </div>
        <button type="submit" form="settings-form"
            class="text-black bg-yellow-300 hover:bg-yellow-400 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
            Save Changes
        </button>
    </div>

    <div class="space-y-4">
        <!-- Enable Registration -->
        <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Enable User Registration</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Allow new users to register accounts</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="settings[enable_registration]" value="0">
                <input type="checkbox" name="settings[enable_registration]" value="1"
                    {{ ($settings['advanced']['enable_registration']->value ?? '1') == '1' ? 'checked' : '' }}
                    class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-400 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-300"></div>
            </label>
        </div>

        <!-- Enable Company Registration -->
        <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Enable Company Registration</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Allow users to register and manage company profiles</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="settings[enable_company_registration]" value="0">
                <input type="checkbox" name="settings[enable_company_registration]" value="1"
                    {{ ($settings['advanced']['enable_company_registration']->value ?? '1') == '1' ? 'checked' : '' }}
                    class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-400 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-300"></div>
            </label>
        </div>

        <!-- Enable Blog -->
        <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Enable Blog</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Enable the blog/news section on the site</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="settings[enable_blog]" value="0">
                <input type="checkbox" name="settings[enable_blog]" value="1"
                    {{ ($settings['advanced']['enable_blog']->value ?? '1') == '1' ? 'checked' : '' }}
                    class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-400 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-300"></div>
            </label>
        </div>

        <!-- Maintenance Mode -->
        <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Maintenance Mode</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Put the site in maintenance mode (admin only)</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="settings[maintenance_mode]" value="0">
                <input type="checkbox" name="settings[maintenance_mode]" value="1"
                    {{ ($settings['advanced']['maintenance_mode']->value ?? '0') == '1' ? 'checked' : '' }}
                    class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-400 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-300"></div>
            </label>
        </div>

        <!-- Google Maps Enabled -->
        <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Enable Google Maps</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Show Google Maps on company detail pages</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="settings[google_maps_enabled]" value="0">
                <input type="checkbox" name="settings[google_maps_enabled]" value="1"
                    {{ ($settings['advanced']['google_maps_enabled']->value ?? '0') == '1' ? 'checked' : '' }}
                    class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-400 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-300"></div>
            </label>
        </div>

        <!-- Google Maps API Key -->
        <div>
            <label for="setting-google_maps_api_key" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Google Maps API Key</label>
            <input type="text" id="setting-google_maps_api_key" name="settings[google_maps_api_key]"
                value="{{ $settings['advanced']['google_maps_api_key']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="Enter your Google Maps API key">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Get your API key from <a href="https://console.cloud.google.com/google/maps-apis" target="_blank" class="text-yellow-500 hover:underline">Google Cloud Console</a>. Enable the Maps JavaScript API and Maps Embed API.</p>
        </div>

        <!-- Enable reCAPTCHA -->
        <div class="flex items-center justify-between p-4 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Enable reCAPTCHA</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Require reCAPTCHA verification on all public forms</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="settings[recaptcha_enabled]" value="0">
                <input type="checkbox" name="settings[recaptcha_enabled]" value="1"
                    {{ ($settings['advanced']['recaptcha_enabled']->value ?? '0') == '1' ? 'checked' : '' }}
                    class="sr-only peer">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-400 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-300"></div>
            </label>
        </div>

        <!-- reCAPTCHA Site Key -->
        <div>
            <label for="setting-recaptcha_site_key" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">reCAPTCHA Site Key</label>
            <input type="text" id="setting-recaptcha_site_key" name="settings[recaptcha_site_key]"
                value="{{ $settings['advanced']['recaptcha_site_key']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="Enter your reCAPTCHA site key">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Get your site key from <a href="https://www.google.com/recaptcha/admin" target="_blank" class="text-yellow-500 hover:underline">Google reCAPTCHA Admin</a></p>
        </div>

        <!-- reCAPTCHA Secret Key -->
        <div>
            <label for="setting-recaptcha_secret_key" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">reCAPTCHA Secret Key</label>
            <input type="password" id="setting-recaptcha_secret_key" name="settings[recaptcha_secret_key]"
                value="{{ $settings['advanced']['recaptcha_secret_key']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="Enter your reCAPTCHA secret key">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Keep this secret. Used for server-side verification.</p>
        </div>

        <!-- Custom CSS -->
        <div>
            <label for="setting-custom_css" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Custom CSS</label>
            <textarea id="setting-custom_css" name="settings[custom_css]" rows="6"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white font-mono text-sm"
                placeholder="/* Add your custom CSS here */
body {
  /* custom styles */
}">{{ $settings['advanced']['custom_css']->value ?? '' }}</textarea>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Custom CSS will be added to all pages</p>
        </div>

        <!-- Custom JS -->
        <div>
            <label for="setting-custom_js" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Custom JavaScript</label>
            <textarea id="setting-custom_js" name="settings[custom_js]" rows="6"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white font-mono text-sm"
                placeholder="// Add your custom JavaScript here
document.addEventListener('DOMContentLoaded', function() {
  // custom scripts
});">{{ $settings['advanced']['custom_js']->value ?? '' }}</textarea>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Custom JavaScript will be added to all pages</p>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-custom-dark shadow-md rounded-lg p-6 mb-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Legal Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage privacy policy and terms of service content</p>
        </div>
        <button type="submit" form="settings-form"
            class="text-black bg-yellow-300 hover:bg-yellow-400 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
            Save Changes
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Privacy Policy -->
        <div class="md:col-span-2">
            <label for="setting-privacy_policy" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Privacy Policy</label>
            <textarea id="setting-privacy_policy" name="settings[privacy_policy]" rows="12"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white font-mono text-sm"
                placeholder="Enter your privacy policy HTML content...">{{ $settings['legal']['privacy_policy']->value ?? '' }}</textarea>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">HTML allowed. Displayed at <code class="bg-gray-100 dark:bg-black px-1 rounded">/privacy</code></p>
        </div>

        <!-- Terms of Service -->
        <div class="md:col-span-2">
            <label for="setting-terms_of_service" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Terms of Service</label>
            <textarea id="setting-terms_of_service" name="settings[terms_of_service]" rows="12"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white font-mono text-sm"
                placeholder="Enter your terms of service HTML content...">{{ $settings['legal']['terms_of_service']->value ?? '' }}</textarea>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">HTML allowed. Displayed at <code class="bg-gray-100 dark:bg-black px-1 rounded">/terms</code></p>
        </div>
    </div>
</div>
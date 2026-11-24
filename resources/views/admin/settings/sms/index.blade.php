<div class="bg-white dark:bg-custom-dark shadow-md rounded-lg p-6 mb-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">SMS Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Configure SMS gateway and notification settings</p>
        </div>
        <button type="submit" form="settings-form"
            class="text-black bg-yellow-300 hover:bg-yellow-400 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
            Save Changes
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- SMS Enabled -->
        <div class="md:col-span-2">
            <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Enable SMS</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Turn SMS notifications on or off globally</p>
                </div>
                <label class="qj-setting-switch">
                    <input type="hidden" name="settings[sms_enabled]" value="0">
                    <input type="checkbox" name="settings[sms_enabled]" value="1"
                        {{ ($settings['sms']['sms_enabled']->value ?? '0') == '1' ? 'checked' : '' }}
                        class="qj-setting-switch-input">
                    <span class="qj-setting-switch-track" aria-hidden="true"></span>
                </label>
            </div>
        </div>

        <!-- SMS Driver -->
        <div>
            <label for="setting-sms_driver" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SMS Driver / Gateway</label>
            <select id="setting-sms_driver" name="settings[sms_driver]"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white">
                <option value="twilio" {{ ($settings['sms']['sms_driver']->value ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                <option value="nexmo" {{ ($settings['sms']['sms_driver']->value ?? '') === 'nexmo' ? 'selected' : '' }}>Nexmo (Vonage)</option>
                <option value="log" {{ ($settings['sms']['sms_driver']->value ?? '') === 'log' ? 'selected' : '' }}>Log (for testing)</option>
                <option value="" {{ ($settings['sms']['sms_driver']->value ?? '') === '' ? 'selected' : '' }}>None</option>
            </select>
        </div>

        <!-- SMS From Number -->
        <div>
            <label for="setting-sms_from_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SMS From Number</label>
            <input type="text" id="setting-sms_from_number" name="settings[sms_from_number]"
                value="{{ $settings['sms']['sms_from_number']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="+6751234567">
        </div>

        <!-- SMS API Key -->
        <div class="md:col-span-2">
            <label for="setting-sms_api_key" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SMS API Key / Secret</label>
            <input type="text" id="setting-sms_api_key" name="settings[sms_api_key]"
                value="{{ $settings['sms']['sms_api_key']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="Enter your SMS API key">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Your API key or secret for the selected SMS gateway</p>
        </div>
    </div>
</div>

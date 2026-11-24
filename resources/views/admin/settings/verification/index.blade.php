<div class="p-6 bg-white dark:bg-custom-dark rounded-lg shadow-md">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Verification Settings</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Configure user and company identity verification requirements.</p>

    @php
        $v = $settings['verification'] ?? [];
        $getVal = function($key) use ($v) {
            return $v[$key]->value ?? '0';
        };
    @endphp

    <div class="space-y-6">
        <!-- Email Verification -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Email Verification</h4>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Require email verification for new users</p>
                </div>
                <label class="qj-setting-switch">
                    <input type="hidden" name="settings[email_verification_required]" value="0">
                    <input type="checkbox" name="settings[email_verification_required]" value="1"
                        {{ $getVal('email_verification_required') == '1' ? 'checked' : '' }}
                        class="qj-setting-switch-input">
                    <span class="qj-setting-switch-track" aria-hidden="true"></span>
                </label>
            </div>
        </div>

        <!-- Phone Verification -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Phone Verification</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Enable phone verification</p>
                    </div>
                    <label class="qj-setting-switch">
                        <input type="hidden" name="settings[phone_verification_enabled]" value="0">
                        <input type="checkbox" name="settings[phone_verification_enabled]" value="1"
                            {{ $getVal('phone_verification_enabled') == '1' ? 'checked' : '' }}
                            class="qj-setting-switch-input">
                        <span class="qj-setting-switch-track" aria-hidden="true"></span>
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Require phone verification for users</p>
                    </div>
                    <label class="qj-setting-switch">
                        <input type="hidden" name="settings[phone_verification_required]" value="0">
                        <input type="checkbox" name="settings[phone_verification_required]" value="1"
                            {{ $getVal('phone_verification_required') == '1' ? 'checked' : '' }}
                            class="qj-setting-switch-input">
                        <span class="qj-setting-switch-track" aria-hidden="true"></span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SMS Verification Template</label>
                    <textarea name="settings[sms_verification_template]" rows="2"
                        class="w-full px-3 py-2 rounded-md text-sm focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">{{ $getVal('sms_verification_template') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Use {code} as placeholder for the verification code</p>
                </div>
            </div>
        </div>

        <!-- Identity/NID Verification -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Identity / NID Verification</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Enable identity verification (NID upload)</p>
                    </div>
                    <label class="qj-setting-switch">
                        <input type="hidden" name="settings[identity_verification_enabled]" value="0">
                        <input type="checkbox" name="settings[identity_verification_enabled]" value="1"
                            {{ $getVal('identity_verification_enabled') == '1' ? 'checked' : '' }}
                            class="qj-setting-switch-input">
                        <span class="qj-setting-switch-track" aria-hidden="true"></span>
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Require identity verification for users</p>
                    </div>
                    <label class="qj-setting-switch">
                        <input type="hidden" name="settings[identity_verification_required]" value="0">
                        <input type="checkbox" name="settings[identity_verification_required]" value="1"
                            {{ $getVal('identity_verification_required') == '1' ? 'checked' : '' }}
                            class="qj-setting-switch-input">
                        <span class="qj-setting-switch-track" aria-hidden="true"></span>
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Auto-approve verified users</p>
                    </div>
                    <label class="qj-setting-switch">
                        <input type="hidden" name="settings[auto_approve_users]" value="0">
                        <input type="checkbox" name="settings[auto_approve_users]" value="1"
                            {{ $getVal('auto_approve_users') == '1' ? 'checked' : '' }}
                            class="qj-setting-switch-input">
                        <span class="qj-setting-switch-track" aria-hidden="true"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Company Verification -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Company Verification</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Require company verification</p>
                    </div>
                    <label class="qj-setting-switch">
                        <input type="hidden" name="settings[company_verification_required]" value="0">
                        <input type="checkbox" name="settings[company_verification_required]" value="1"
                            {{ $getVal('company_verification_required') == '1' ? 'checked' : '' }}
                            class="qj-setting-switch-input">
                        <span class="qj-setting-switch-track" aria-hidden="true"></span>
                    </label>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Auto-approve companies after verification</p>
                    </div>
                    <label class="qj-setting-switch">
                        <input type="hidden" name="settings[auto_approve_companies]" value="0">
                        <input type="checkbox" name="settings[auto_approve_companies]" value="1"
                            {{ $getVal('auto_approve_companies') == '1' ? 'checked' : '' }}
                            class="qj-setting-switch-input">
                        <span class="qj-setting-switch-track" aria-hidden="true"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- SMS Driver -->
        <div class="pt-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Verification SMS Driver</label>
            <select name="settings[verification_sms_driver]"
                class="w-full px-3 py-2 rounded-md text-sm focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                <option value="log" {{ $getVal('verification_sms_driver') == 'log' ? 'selected' : '' }}>Log (for testing)</option>
                <option value="twilio" {{ $getVal('verification_sms_driver') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                <option value="vonage" {{ $getVal('verification_sms_driver') == 'vonage' ? 'selected' : '' }}>Vonage</option>
                <option value="messagebird" {{ $getVal('verification_sms_driver') == 'messagebird' ? 'selected' : '' }}>MessageBird</option>
            </select>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="button" class="save-settings-btn bg-yellow-300 hover:bg-yellow-400 text-black font-medium py-2 px-6 rounded-lg transition duration-200">
            Save Verification Settings
        </button>
    </div>
</div>

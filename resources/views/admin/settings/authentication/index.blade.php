<div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sign-in Methods</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">These choices apply to the website and Android app.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="email_login_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email and password sign-in</label>
            <select id="email_login_enabled" name="settings[email_login_enabled]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                <option value="1" {{ \App\Services\AuthMethodSettings::emailEnabled() ? 'selected' : '' }}>Enabled</option>
                <option value="0" {{ \App\Services\AuthMethodSettings::emailEnabled() ? '' : 'selected' }}>Disabled</option>
            </select>
        </div>

        <div>
            <label for="google_login_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Google sign-in</label>
            <select id="google_login_enabled" name="settings[google_login_enabled]" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
                <option value="1" {{ filter_var($settings['authentication']['google_login_enabled']->value ?? '0', FILTER_VALIDATE_BOOLEAN) ? 'selected' : '' }}>Enabled</option>
                <option value="0" {{ filter_var($settings['authentication']['google_login_enabled']->value ?? '0', FILTER_VALIDATE_BOOLEAN) ? '' : 'selected' }}>Disabled</option>
            </select>
            <p class="text-xs text-gray-400 mt-1">Google is available only after client credentials are set.</p>
        </div>

        <div>
            <label for="google_client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Google OAuth Client ID</label>
            <input id="google_client_id" type="text" name="settings[google_client_id]" value="{{ $settings['authentication']['google_client_id']->value ?? '' }}" autocomplete="off" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
        </div>

        <div>
            <label for="google_client_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Google OAuth Client Secret</label>
            <input id="google_client_secret" type="password" name="settings[google_client_secret]" value="" autocomplete="new-password" placeholder="{{ \App\Services\AuthMethodSettings::googleClientSecret() !== '' ? 'Saved — leave blank to keep current secret' : 'Paste client secret' }}" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
        </div>

        <div class="md:col-span-2">
            <label for="google_redirect_uri" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Google Redirect URI</label>
            <input id="google_redirect_uri" type="url" name="settings[google_redirect_uri]" value="{{ ($settings['authentication']['google_redirect_uri']->value ?? '') ?: url('/auth/google/callback') }}" class="w-full rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm px-4 py-2">
            <p class="text-xs text-gray-400 mt-1">Add this exact URI to the authorized redirect URIs in the Google Cloud OAuth client.</p>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="button" class="save-settings-btn bg-green-400 hover:bg-green-500 text-black font-medium rounded-full px-6 py-2 text-sm">Save Sign-in Methods</button>
    </div>
</div>

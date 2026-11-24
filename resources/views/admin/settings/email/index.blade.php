<div class="bg-white dark:bg-custom-dark shadow-md rounded-lg p-6 mb-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Email Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Configure your email server and sender information</p>
        </div>
        <button type="submit" form="settings-form"
            class="text-black bg-yellow-300 hover:bg-yellow-400 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
            Save Changes
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Mail Driver -->
        <div>
            <label for="setting-mail_driver" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mail Driver</label>
            <select id="setting-mail_driver" name="settings[mail_driver]"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white">
                <option value="smtp" {{ ($settings['email']['mail_driver']->value ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                <option value="sendmail" {{ ($settings['email']['mail_driver']->value ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                <option value="mailgun" {{ ($settings['email']['mail_driver']->value ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                <option value="ses" {{ ($settings['email']['mail_driver']->value ?? '') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                <option value="log" {{ ($settings['email']['mail_driver']->value ?? '') === 'log' ? 'selected' : '' }}>Log (for testing)</option>
            </select>
        </div>

        <!-- Mail Host -->
        <div>
            <label for="setting-mail_host" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mail Host</label>
            <input type="text" id="setting-mail_host" name="settings[mail_host]"
                value="{{ $settings['email']['mail_host']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="smtp.example.com">
        </div>

        <!-- Mail Port -->
        <div>
            <label for="setting-mail_port" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mail Port</label>
            <input type="number" id="setting-mail_port" name="settings[mail_port]"
                value="{{ $settings['email']['mail_port']->value ?? '587' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="587">
        </div>

        <!-- Mail Encryption -->
        <div>
            <label for="setting-mail_encryption" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mail Encryption</label>
            <select id="setting-mail_encryption" name="settings[mail_encryption]"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white">
                <option value="tls" {{ ($settings['email']['mail_encryption']->value ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                <option value="ssl" {{ ($settings['email']['mail_encryption']->value ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                <option value="" {{ ($settings['email']['mail_encryption']->value ?? '') === '' ? 'selected' : '' }}>None</option>
            </select>
        </div>

        <!-- Mail Username -->
        <div>
            <label for="setting-mail_username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mail Username</label>
            <input type="text" id="setting-mail_username" name="settings[mail_username]"
                value="{{ $settings['email']['mail_username']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="your@email.com">
        </div>

        <!-- Mail Password -->
        <div>
            <label for="setting-mail_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mail Password</label>
            <input type="password" id="setting-mail_password" name="settings[mail_password]"
                value="{{ $settings['email']['mail_password']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="••••••••">
        </div>

        <!-- Mail From Address -->
        <div>
            <label for="setting-mail_from_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">From Address</label>
            <input type="email" id="setting-mail_from_address" name="settings[mail_from_address]"
                value="{{ $settings['email']['mail_from_address']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="noreply@example.com">
        </div>

        <!-- Mail From Name -->
        <div>
            <label for="setting-mail_from_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">From Name</label>
            <input type="text" id="setting-mail_from_name" name="settings[mail_from_name]"
                value="{{ $settings['email']['mail_from_name']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="BiraConsultant">
        </div>
    </div>
</div>

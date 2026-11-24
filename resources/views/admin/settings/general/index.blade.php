<div class="bg-white dark:bg-custom-dark shadow-md rounded-lg p-6 mb-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">General Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Configure your site's basic information</p>
        </div>
        <button type="submit" form="settings-form"
            class="text-black bg-yellow-300 hover:bg-yellow-400 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
            Save Changes
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Site Name -->
        <div class="md:col-span-2">
            <label for="setting-site_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Site Name</label>
            <input type="text" id="setting-site_name" name="settings[site_name]"
                value="{{ $settings['general']['site_name']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="Enter your site name">
        </div>

        <!-- Site Description -->
        <div class="md:col-span-2">
            <label for="setting-site_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Site Description</label>
            <textarea id="setting-site_description" name="settings[site_description]" rows="3"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white"
                placeholder="A brief description of your site">{{ $settings['general']['site_description']->value ?? '' }}</textarea>
        </div>

        <!-- Site Logo -->
        <div>
            <label for="setting-site_logo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Site Logo URL</label>
            <input type="text" id="setting-site_logo" name="settings[site_logo]"
                value="{{ $settings['general']['site_logo']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="/images/logo.png">
        </div>

        <!-- Default Language -->
        <div>
            <label for="setting-default_language" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Default Language</label>
            <select id="setting-default_language" name="settings[default_language]"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white">
                <option value="en" {{ ($settings['general']['default_language']->value ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                <option value="fr" {{ ($settings['general']['default_language']->value ?? '') === 'fr' ? 'selected' : '' }}>French</option>
                <option value="de" {{ ($settings['general']['default_language']->value ?? '') === 'de' ? 'selected' : '' }}>German</option>
                <option value="es" {{ ($settings['general']['default_language']->value ?? '') === 'es' ? 'selected' : '' }}>Spanish</option>
                <option value="zh" {{ ($settings['general']['default_language']->value ?? '') === 'zh' ? 'selected' : '' }}>Chinese</option>
            </select>
        </div>

        <!-- Timezone -->
        <div>
            <label for="setting-timezone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Timezone</label>
            <select id="setting-timezone" name="settings[timezone]"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white">
                <option value="UTC" {{ ($settings['general']['timezone']->value ?? '') === 'UTC' ? 'selected' : '' }}>UTC</option>
                <option value="Pacific/Port_Moresby" {{ ($settings['general']['timezone']->value ?? '') === 'Pacific/Port_Moresby' ? 'selected' : '' }}>Pacific/Port Moresby (PGT)</option>
                <option value="Australia/Sydney" {{ ($settings['general']['timezone']->value ?? '') === 'Australia/Sydney' ? 'selected' : '' }}>Australia/Sydney (AEST)</option>
                <option value="America/New_York" {{ ($settings['general']['timezone']->value ?? '') === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                <option value="Europe/London" {{ ($settings['general']['timezone']->value ?? '') === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                <option value="Asia/Shanghai" {{ ($settings['general']['timezone']->value ?? '') === 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai (CST)</option>
            </select>
        </div>

        <!-- Date Format -->
        <div>
            <label for="setting-date_format" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date Format</label>
            <select id="setting-date_format" name="settings[date_format]"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white">
                <option value="Y-m-d" {{ ($settings['general']['date_format']->value ?? '') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                <option value="d/m/Y" {{ ($settings['general']['date_format']->value ?? '') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                <option value="m/d/Y" {{ ($settings['general']['date_format']->value ?? '') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                <option value="d-m-Y" {{ ($settings['general']['date_format']->value ?? '') === 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                <option value="F j, Y" {{ ($settings['general']['date_format']->value ?? '') === 'F j, Y' ? 'selected' : '' }}>Month DD, YYYY</option>
            </select>
        </div>
    </div>
</div>

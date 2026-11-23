<div class="bg-white dark:bg-custom-dark shadow-md rounded-lg p-6 mb-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Company Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Configure company-related options</p>
        </div>
        <button type="submit" form="settings-form"
            class="text-black bg-yellow-300 hover:bg-yellow-400 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
            Save Changes
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Default Company Logo -->
        <div>
            <label for="setting-default_company_logo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Default Company Logo URL</label>
            <input type="text" id="setting-default_company_logo" name="settings[default_company_logo]"
                value="{{ $settings['company']['default_company_logo']->value ?? '' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="/images/company-default.png">
        </div>

        <!-- Max Companies Per User -->
        <div>
            <label for="setting-max_companies_per_user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max Companies Per User</label>
            <input type="number" id="setting-max_companies_per_user" name="settings[max_companies_per_user]"
                value="{{ $settings['company']['max_companies_per_user']->value ?? '3' }}" min="1" max="100"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="3">
        </div>

        <!-- Company Approval Required -->
        <div class="md:col-span-2">
            <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Company Approval Required</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Require admin approval before a company becomes active</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="settings[company_approval_required]" value="0">
                    <input type="checkbox" name="settings[company_approval_required]" value="1"
                        {{ ($settings['company']['company_approval_required']->value ?? '1') == '1' ? 'checked' : '' }}
                        class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-400 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-300"></div>
                </label>
            </div>
        </div>

        <!-- Company Categories -->
        <div class="md:col-span-2">
            <label for="setting-company_categories" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company Categories</label>
            <textarea id="setting-company_categories" name="settings[company_categories]" rows="4"
                class="block w-full p-2.5 text-gray-900 rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus: dark:text-white"
                placeholder="Enter one category per line">{{ $settings['company']['company_categories']->value ?? '' }}</textarea>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter each category on a new line</p>
        </div>
    </div>
</div>

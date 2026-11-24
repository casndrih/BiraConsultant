<div class="bg-white dark:bg-custom-dark shadow-md rounded-lg p-6 mb-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Jobs Settings</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Configure job listing and application options</p>
        </div>
        <button type="submit" form="settings-form"
            class="text-black bg-yellow-300 hover:bg-yellow-400 font-medium rounded-lg text-sm px-6 py-2.5 transition-colors">
            Save Changes
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Jobs Per Page -->
        <div>
            <label for="setting-jobs_per_page" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jobs Per Page</label>
            <input type="number" id="setting-jobs_per_page" name="settings[jobs_per_page]"
                value="{{ $settings['jobs']['jobs_per_page']->value ?? '12' }}" min="1" max="100"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="12">
        </div>

        <!-- Job Expiry Days -->
        <div>
            <label for="setting-job_expiry_days" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Job Expiry Days</label>
            <input type="number" id="setting-job_expiry_days" name="settings[job_expiry_days]"
                value="{{ $settings['jobs']['job_expiry_days']->value ?? '30' }}" min="1" max="365"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="30">
        </div>

        <!-- Featured Job Price -->
        <div>
            <label for="setting-featured_job_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Featured Job Price</label>
            <input type="text" id="setting-featured_job_price" name="settings[featured_job_price]"
                value="{{ $settings['jobs']['featured_job_price']->value ?? '0' }}"
                class="block w-full p-2.5 text-gray-900  rounded-lg bg-gray-50 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                placeholder="0">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Set to 0 for free featured jobs</p>
        </div>

        <!-- Job Approval Required -->
        <div class="md:col-span-2">
            <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Job Approval Required</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Require admin approval before a job is published</p>
                </div>
                <label class="qj-setting-switch">
                    <input type="hidden" name="settings[job_approval_required]" value="0">
                    <input type="checkbox" name="settings[job_approval_required]" value="1"
                        {{ ($settings['jobs']['job_approval_required']->value ?? '1') == '1' ? 'checked' : '' }}
                        class="qj-setting-switch-input">
                    <span class="qj-setting-switch-track" aria-hidden="true"></span>
                </label>
            </div>
        </div>

        <!-- Job Categories Enabled -->
        <div class="md:col-span-2">
            <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Job Categories Enabled</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Enable job categories for filtering and organization</p>
                </div>
                <label class="qj-setting-switch">
                    <input type="hidden" name="settings[job_categories_enabled]" value="0">
                    <input type="checkbox" name="settings[job_categories_enabled]" value="1"
                        {{ ($settings['jobs']['job_categories_enabled']->value ?? '1') == '1' ? 'checked' : '' }}
                        class="qj-setting-switch-input">
                    <span class="qj-setting-switch-track" aria-hidden="true"></span>
                </label>
            </div>
        </div>

        <!-- Job Types Enabled -->
        <div class="md:col-span-2">
            <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Job Types Enabled</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Enable job types (Full-time, Part-time, etc.)</p>
                </div>
                <label class="qj-setting-switch">
                    <input type="hidden" name="settings[job_types_enabled]" value="0">
                    <input type="checkbox" name="settings[job_types_enabled]" value="1"
                        {{ ($settings['jobs']['job_types_enabled']->value ?? '1') == '1' ? 'checked' : '' }}
                        class="qj-setting-switch-input">
                    <span class="qj-setting-switch-track" aria-hidden="true"></span>
                </label>
            </div>
        </div>

        <!-- Experience Levels Enabled -->
        <div class="md:col-span-2">
            <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Experience Levels Enabled</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Enable experience level filtering for jobs</p>
                </div>
                <label class="qj-setting-switch">
                    <input type="hidden" name="settings[experience_levels_enabled]" value="0">
                    <input type="checkbox" name="settings[experience_levels_enabled]" value="1"
                        {{ ($settings['jobs']['experience_levels_enabled']->value ?? '1') == '1' ? 'checked' : '' }}
                        class="qj-setting-switch-input">
                    <span class="qj-setting-switch-track" aria-hidden="true"></span>
                </label>
            </div>
        </div>

        <!-- Skills Enabled -->
        <div class="md:col-span-2">
            <div class="flex items-center justify-between p-4  dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-black">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Skills Enabled</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Enable skills tagging for jobs and user profiles</p>
                </div>
                <label class="qj-setting-switch">
                    <input type="hidden" name="settings[skills_enabled]" value="0">
                    <input type="checkbox" name="settings[skills_enabled]" value="1"
                        {{ ($settings['jobs']['skills_enabled']->value ?? '1') == '1' ? 'checked' : '' }}
                        class="qj-setting-switch-input">
                    <span class="qj-setting-switch-track" aria-hidden="true"></span>
                </label>
            </div>
        </div>
    </div>
</div>

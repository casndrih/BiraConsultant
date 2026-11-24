<div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upload Limits</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Set the maximum file size for each upload type. Limits are in KB.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Profile Image -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Profile Image
            </label>
            <div class="flex items-center gap-2">
                <input type="number"
                    name="settings[upload_limit_profile_image]"
                    value="{{ $settings['uploads']['upload_limit_profile_image']->value ?? '' }}"
                    min="256" max="51200"
                    placeholder="2048"
                    class="flex-1 rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm focus:ring-yellow-300 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">KB</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">User profile pictures. Default: 2048 KB (2MB)</p>
        </div>

        <!-- Profile Cover Image -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Profile Cover Image</label>
            <div class="flex items-center gap-2">
                <input type="number" name="settings[upload_limit_profile_cover_image]"
                    value="{{ $settings['uploads']['upload_limit_profile_cover_image']->value ?? '' }}"
                    min="256" max="51200" placeholder="5120"
                    class="flex-1 rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm focus:ring-yellow-300 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">KB</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Profile cover uploads. Default: 5120 KB (5MB)</p>
        </div>

        <!-- Company Logo -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Company Logo
            </label>
            <div class="flex items-center gap-2">
                <input type="number"
                    name="settings[upload_limit_company_logo]"
                    value="{{ $settings['uploads']['upload_limit_company_logo']->value ?? '' }}"
                    min="256" max="51200"
                    placeholder="2048"
                    class="flex-1 rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm focus:ring-yellow-300 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">KB</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Company logo uploads. Default: 2048 KB (2MB)</p>
        </div>

        <!-- Company Documents -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Company Documents
            </label>
            <div class="flex items-center gap-2">
                <input type="number"
                    name="settings[upload_limit_company_docs]"
                    value="{{ $settings['uploads']['upload_limit_company_docs']->value ?? '' }}"
                    min="256" max="51200"
                    placeholder="2048"
                    class="flex-1 rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm focus:ring-yellow-300 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">KB</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Registration & tax documents. Default: 2048 KB (2MB)</p>
        </div>

        <!-- Application Files -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Application Files
            </label>
            <div class="flex items-center gap-2">
                <input type="number"
                    name="settings[upload_limit_application_files]"
                    value="{{ $settings['uploads']['upload_limit_application_files']->value ?? '' }}"
                    min="256" max="51200"
                    placeholder="2048"
                    class="flex-1 rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm focus:ring-yellow-300 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">KB</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">CV, resume & cover letter. Default: 2048 KB (2MB)</p>
        </div>

        <!-- Blog Images -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Blog Images
            </label>
            <div class="flex items-center gap-2">
                <input type="number"
                    name="settings[upload_limit_blog_images]"
                    value="{{ $settings['uploads']['upload_limit_blog_images']->value ?? '' }}"
                    min="256" max="51200"
                    placeholder="2048"
                    class="flex-1 rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm focus:ring-yellow-300 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">KB</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Blog featured images. Default: 2048 KB (2MB)</p>
        </div>

        <!-- NID Documents -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                NID Documents
            </label>
            <div class="flex items-center gap-2">
                <input type="number"
                    name="settings[upload_limit_nid_docs]"
                    value="{{ $settings['uploads']['upload_limit_nid_docs']->value ?? '' }}"
                    min="256" max="51200"
                    placeholder="2048"
                    class="flex-1 rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm focus:ring-yellow-300 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">KB</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">National ID front/back. Default: 2048 KB (2MB)</p>
        </div>

        <!-- Admin User Cover Image -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin User Cover Image</label>
            <div class="flex items-center gap-2">
                <input type="number" name="settings[upload_limit_admin_user_cover]"
                    value="{{ $settings['uploads']['upload_limit_admin_user_cover']->value ?? '' }}"
                    min="256" max="51200" placeholder="5120"
                    class="flex-1 rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm focus:ring-yellow-300 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">KB</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Cover uploads from admin. Default: 5120 KB (5MB)</p>
        </div>

        <!-- Admin User Image -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Admin User Image
            </label>
            <div class="flex items-center gap-2">
                <input type="number"
                    name="settings[upload_limit_admin_user_image]"
                    value="{{ $settings['uploads']['upload_limit_admin_user_image']->value ?? '' }}"
                    min="256" max="51200"
                    placeholder="2048"
                    class="flex-1 rounded-full bg-gray-100 dark:bg-black border-0 text-gray-900 dark:text-white text-sm focus:ring-yellow-300 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">KB</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">User image from admin panel. Default: 2048 KB (2MB)</p>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="button" class="save-settings-btn bg-yellow-300 hover:bg-yellow-400 text-black font-medium rounded-full px-6 py-2 text-sm">
            Save Upload Limits
        </button>
    </div>
</div>

@extends('admin.app')

@section('content')
<section class="bg-gray-100 dark:bg-black min-h-screen py-4">
    <div class="mx-4">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage your application configuration</p>
        </div>

        <!-- Search Bar -->
        <div class="relative overflow-x-auto mb-4 shadow-md rounded-lg">
            <div class="flex items-center justify-between py-4 px-4 bg-white dark:bg-custom-dark rounded-lg">
                <label for="setting-search" class="sr-only">Search Settings</label>
                <div class="relative flex items-center flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 flex items-center ml-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-600" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <form action="{{ route('admin.setting.search') }}" method="GET" class="flex-1">
                        <input type="text" id="setting-search" name="query"
                            value="{{ request()->query('query', '') }}"
                            class="block w-full p-2 pl-10 text-base text-gray-900  rounded-full bg-gray-100 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300 dark:text-white"
                            placeholder="Search settings by key or value...">
                    </form>
                </div>
                @if(request()->query('query'))
                    <a href="{{ route('admin.settings.index') }}" class="ml-4 text-sm text-yellow-400 hover:text-yellow-300 font-medium">Clear Search</a>
                @endif
            </div>
        </div>

        <!-- Tabs -->
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="settingsTabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg text-yellow-400 border-yellow-400" id="general-tab" data-tabs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                        General
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="company-tab" data-tabs-target="#company" type="button" role="tab" aria-controls="company" aria-selected="false">
                        Company
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="email-tab" data-tabs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">
                        Email
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="sms-tab" data-tabs-target="#sms" type="button" role="tab" aria-controls="sms" aria-selected="false">
                        SMS
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="jobs-tab" data-tabs-target="#jobs" type="button" role="tab" aria-controls="jobs" aria-selected="false">
                        Jobs
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="advanced-tab" data-tabs-target="#advanced" type="button" role="tab" aria-controls="advanced" aria-selected="false">
                        Advanced
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="verification-tab" data-tabs-target="#verification" type="button" role="tab" aria-controls="verification" aria-selected="false">
                        Verification
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="authentication-tab" data-tabs-target="#authentication" type="button" role="tab" aria-controls="authentication" aria-selected="false">
                        Sign-in Methods
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="ads-tab" data-tabs-target="#ads" type="button" role="tab" aria-controls="ads" aria-selected="false">
                        Mobile Ads
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="legal-tab" data-tabs-target="#legal" type="button" role="tab" aria-controls="legal" aria-selected="false">
                        Legal
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400" id="uploads-tab" data-tabs-target="#uploads" type="button" role="tab" aria-controls="uploads" aria-selected="false">
                        Upload Limits
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Contents -->
        <form id="settings-form" action="{{ route('admin.setting.update') }}" method="POST">
            @csrf

            <div id="settingsTabContent">
                <!-- General Settings -->
                <div class="block" id="general" role="tabpanel" aria-labelledby="general-tab">
                    @include('admin.settings.general.index')
                </div>

                <!-- Company Settings -->
                <div class="hidden" id="company" role="tabpanel" aria-labelledby="company-tab">
                    @include('admin.settings.company.index')
                </div>

                <!-- Email Settings -->
                <div class="hidden" id="email" role="tabpanel" aria-labelledby="email-tab">
                    @include('admin.settings.email.index')
                </div>

                <!-- SMS Settings -->
                <div class="hidden" id="sms" role="tabpanel" aria-labelledby="sms-tab">
                    @include('admin.settings.sms.index')
                </div>

                <!-- Jobs Settings -->
                <div class="hidden" id="jobs" role="tabpanel" aria-labelledby="jobs-tab">
                    @include('admin.settings.jobs.index')
                </div>

                <!-- Advanced Settings -->
                <div class="hidden" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                    @include('admin.settings.advanced.index')
                </div>

                <!-- Verification Settings -->
                <div class="hidden" id="verification" role="tabpanel" aria-labelledby="verification-tab">
                    @include('admin.settings.verification.index')
                </div>

                <div class="hidden" id="authentication" role="tabpanel" aria-labelledby="authentication-tab">
                    @include('admin.settings.authentication.index')
                </div>

                <div class="hidden" id="ads" role="tabpanel" aria-labelledby="ads-tab">
                    @include('admin.settings.ads.index')
                </div>

                <!-- Legal Settings -->
                <div class="hidden" id="legal" role="tabpanel" aria-labelledby="legal-tab">
                    @include('admin.settings.legal.index')
                </div>

                <!-- Upload Limits Settings -->
                <div class="hidden" id="uploads" role="tabpanel" aria-labelledby="uploads-tab">
                    @include('admin.settings.uploads.index')
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[role="tab"]');
    const tabPanels = document.querySelectorAll('[role="tabpanel"]');

    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const targetSelector = this.getAttribute('data-tabs-target');
            const targetPanel = document.querySelector(targetSelector);

            // Hide all panels
            tabPanels.forEach(panel => {
                panel.classList.add('hidden');
                panel.classList.remove('block');
            });

            // Reset all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('text-yellow-400', 'border-yellow-400');
                btn.classList.add('text-gray-500', 'dark:text-gray-400', 'border-transparent');
                btn.setAttribute('aria-selected', 'false');
            });

            // Show target panel
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
                targetPanel.classList.add('block');
            }

            // Activate active button
            this.classList.remove('text-gray-500', 'dark:text-gray-400', 'border-transparent');
            this.classList.add('text-yellow-400', 'border-yellow-400');
            this.setAttribute('aria-selected', 'true');
        });
    });

    // Save button click handler - intercept and submit form
    const saveButtons = document.querySelectorAll('.save-settings-btn');
    saveButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('settings-form').submit();
        });
    });
});
</script>
@endsection

@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-dashboard-page">
    <div class="qj-dashboard-hero">
        <div class="qj-dashboard-hero__inner">
            <h1 class="text-2xl font-bold">Notification Preferences</h1>
            <p class="opacity-80 mt-1">Choose how you want to receive updates</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="border bg-green-100 border-green-400 text-green-700 dark:bg-green-900/30 dark:border-green-600 dark:text-green-300 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                <form action="{{ route('user.notifications.preferences.update') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        {{-- Email Notifications --}}
                        <div class="flex items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700">
                            <div>
                                <h3 class="text-lg font-medium text-black dark:text-white">Email Notifications</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Receive notifications about followed companies and job updates via email.
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="notify_email" value="0">
                                <input type="checkbox" name="notify_email" value="1"
                                       {{ $user->notify_email ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-400"></div>
                            </label>
                        </div>

                        {{-- Followed Company Jobs --}}
                        <div class="flex items-center justify-between py-4">
                            <div>
                                <h3 class="text-lg font-medium text-black dark:text-white">Followed Company Jobs</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Get notified when a company you follow posts a new job opening.
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="notify_followed_company_jobs" value="0">
                                <input type="checkbox" name="notify_followed_company_jobs" value="1"
                                       {{ $user->notify_followed_company_jobs ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-400"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('user.dashboard') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            ← Back to Dashboard
                        </a>
                        <button type="submit"
                                class="bg-yellow-300 hover:bg-yellow-400 text-black px-6 py-2 rounded-lg text-sm font-medium transition duration-200">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            {{-- Quick Links --}}
            <div class="mt-6 bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-black dark:text-white mb-4">Quick Links</h3>
                <div class="space-y-3">
                    <a href="{{ route('user.notifications') }}" class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        <span>View All Notifications</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('user.dashboard') }}" class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                        <span>My Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

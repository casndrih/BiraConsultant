@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-dashboard-page">
    <!-- Dashboard Header -->
    <div class="qj-dashboard-hero">
        <div class="qj-dashboard-hero__inner">
            <div class="flex min-w-0 flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h1 class="mb-2 break-words text-2xl font-bold sm:text-3xl">Welcome back, {{ $user->name }}!</h1>
                    <p class="text-base opacity-90 sm:text-lg">Manage your job search and applications</p>
                </div>
                <div class="hidden text-left sm:block sm:text-right">
                    <div class="mb-2 flex flex-wrap items-center gap-2 sm:justify-end">
                        @if($user->email_verified_at)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Email ✓</span>
                        @endif
                        @if($user->phone_verified)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Phone ✓</span>
                        @endif
                        @if($user->isIdentityVerified())
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">ID ✓</span>
                        @elseif($user->nid_front_path)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">ID Pending</span>
                        @endif
                    </div>
                    <div class="text-sm opacity-75">Member since</div>
                    <div class="text-lg font-semibold">{{ $user->created_at->format('M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Verification Alert -->
        @if(!$user->email_verified_at || !$user->isIdentityVerified())
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-yellow-300 dark:border-yellow-700 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-2">Complete Your Verification</h3>
            <div class="flex flex-wrap gap-3 text-xs">
                @if(!$user->email_verified_at)
                    <a href="{{ route('user.profile') }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-300 hover:bg-yellow-400 text-black font-medium rounded-md transition">
                        Verify Email
                    </a>
                @endif
                @if(!$user->nid_front_path && $user->identity_verified != 1)
                    <a href="{{ route('user.profile') }}#tab-verification" class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-300 hover:bg-yellow-400 text-black font-medium rounded-md transition">
                        Upload NID
                    </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="qj-dashboard-stats-grid mb-8">
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-black dark:text-white">{{ $stats['total_applications'] }}</div>
                <div class="text-xs text-gray-500">Applications</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-blue-600">{{ $stats['received'] }}</div>
                <div class="text-xs text-gray-500">Received</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-yellow-600">{{ $stats['under_review'] }}</div>
                <div class="text-xs text-gray-500">Reviewing</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-purple-600">{{ $stats['interview'] }}</div>
                <div class="text-xs text-gray-500">Interview</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-green-600">{{ $stats['job_offer'] }}</div>
                <div class="text-xs text-gray-500">Offers</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-emerald-600">{{ $stats['hired'] }}</div>
                <div class="text-xs text-gray-500">Hired</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-xs text-gray-500">Rejected</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-yellow-600">{{ $stats['saved_jobs'] }}</div>
                <div class="text-xs text-gray-500">Saved</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Recent Applications -->
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-black dark:text-white">Recent Applications</h2>
                        <a href="{{ route('user.applications') }}" class="text-yellow-500 hover:text-yellow-600 text-sm font-medium">
                            View All →
                        </a>
                    </div>

                    @if($applications->count() > 0)
                        <div class="space-y-4">
                            @foreach($applications as $application)
                                <div class=" rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-medium text-black dark:text-white hover:text-yellow-500">
                                                <a href="{{ route('job.detail', $application->job->slug) }}">
                                                    {{ $application->job->title }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                @if($application->job->company) @include('components.company-popover', ['company' => $application->job->company]) @endif • {{ $application->job->location ?? 'Remote' }}
                                            </p>
                                            <div class="flex items-center gap-3 mt-2">
                                                <span class="text-xs px-2 py-1 rounded-full {{ $application->getStatusColorClassAttribute() }}">
                                                    {{ $application->getStatusLabelAttribute() }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    Applied {{ $application->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-lg border border-dashed border-gray-300 px-5 py-8 text-center dark:border-gray-600">
                            <p class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">No applications yet</p>
                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">When you apply for a job, your application progress will appear here.</p>
                            <a href="{{ route('jobs') }}" class="inline-flex min-h-10 items-center justify-center rounded-lg bg-yellow-300 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-yellow-400 focus:outline-none focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-800">Browse jobs</a>
                        </div>
                                        @endif
                </div>

                <!-- Recommended Jobs -->
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-black dark:text-white mb-6">Recommended for You</h2>
                    <div class="space-y-4">
                        @forelse($recommendedJobs as $job)
                            <div class=" rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-start sm:gap-4">
                                    <div class="flex-shrink-0">
                                        @if($job->company && $job->company->logo)
                                            <img src="{{ asset('storage/' . $job->company->logo) }}" alt="{{ $job->company->name }}" class="w-10 h-10 rounded-md object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <span class="text-sm font-bold text-gray-500">{{ substr(optional($job->company)->name ?? 'C', 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-medium text-black dark:text-white hover:text-yellow-500 mb-1">
                                            <a href="{{ route('job.detail', $job->slug) }}">{{ $job->title }}</a>
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                            @if($job->company) @include('components.company-popover', ['company' => $job->company]) @endif • {{ $job->location ?? 'Remote' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-500">{{ Str::limit(strip_tags($job->description), 80) }}</p>
                                    </div>
                                    <div class="max-w-full text-left sm:flex-shrink-0 sm:text-right">
                                        <span class="text-sm font-medium text-black dark:text-white">{{ $job->salary ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-lg border border-dashed border-gray-300 px-5 py-8 text-center dark:border-gray-600">
                                <p class="mb-2 text-sm font-semibold text-gray-900 dark:text-white">No recommendations available yet</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Check back soon as new jobs are posted.</p>
                            </div>
                        @endforelse
                    </div>
                    <a href="{{ route('jobs') }}" class="inline-block mt-6 text-yellow-500 hover:text-yellow-600 font-medium">
                        View All Jobs →
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('jobs') }}"
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-300 dark:hover:bg-yellow-300 transition group">
                            <svg class="w-5 h-5 text-yellow-500 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-black">Browse Jobs</span>
                        </a>
                        <a href="{{ route('user.profile') }}"
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-300 dark:hover:bg-yellow-300 transition group">
                            <svg class="w-5 h-5 text-yellow-500 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-black">Edit Profile</span>
                        </a>
                        <a href="{{ route('user.applications') }}"
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-300 dark:hover:bg-yellow-300 transition group">
                            <svg class="w-5 h-5 text-yellow-500 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-black">My Applications</span>
                        </a>
                        <a href="{{ route('user.notifications') }}"
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-300 dark:hover:bg-yellow-300 transition group">
                            <svg class="w-5 h-5 text-yellow-500 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-black">Notifications</span>
                        </a>
                        <a href="{{ route('user.notifications.preferences') }}"
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-300 dark:hover:bg-yellow-300 transition group">
                            <svg class="w-5 h-5 text-yellow-500 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-black">Notification Settings</span>
                        </a>
                        @if(!$user->company)
                        <a href="{{ route('company.register') }}"
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-300 dark:hover:bg-yellow-300 transition group">
                            <svg class="w-5 h-5 text-yellow-500 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-black">Register Company</span>
                        </a>
                        @else
                        <a href="{{ route('company.dashboard') }}"
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-300 dark:hover:bg-yellow-300 transition group">
                            <svg class="w-5 h-5 text-yellow-500 group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-black">My Company Dashboard</span>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Profile Completion -->
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Profile Completion</h3>
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Complete your profile</span>
                            <span class="font-medium text-black dark:text-white">{{ $user->profile_completion }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-yellow-300 h-2 rounded-full transition-all" style="width: {{ $user->profile_completion }}%"></div>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $user->name ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Personal information</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $user->bio ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Bio</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $user->image ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Profile photo</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $user->skills->count() > 0 ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Skills</span>
                        </div>
                    </div>
                    <a href="{{ route('user.profile') }}"
                       class="inline-block mt-4 text-yellow-500 hover:text-yellow-600 text-sm font-medium">
                        Complete Profile →
                    </a>
                </div>

                <!-- Saved Jobs -->
                @if($savedJobs->count() > 0)
                    <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Saved Jobs</h3>
                        <div class="space-y-3">
                            @foreach($savedJobs as $savedJob)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-3 last:border-0 last:pb-0">
                                    <a href="{{ route('job.detail', $savedJob->slug) }}" class="text-sm font-medium text-black dark:text-white hover:text-yellow-500">
                                        {{ $savedJob->title }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">@if($savedJob->company) @include('components.company-popover', ['company' => $savedJob->company]) @endif</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

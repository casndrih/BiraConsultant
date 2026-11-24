@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
@include('partials.skeleton-styles')
<div class="qj-detail-page">
    <!-- Skeleton Loading -->
    <div id="company-detail-skeleton">
        @include('components.skeleton-company-detail')
    </div>

    <!-- Real Content (hidden initially) -->
    <div id="company-detail-content" class="hidden">
    <!-- Company Header -->
    <div class="qj-detail-topbar shadow-sm">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('companies') }}" class="qj-inline-link flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Companies
                </a>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $jobs->total() }} job{{ $jobs->total() != 1 ? 's' : '' }} available
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @include('partials.adsense', ['placement' => 'company_detail'])
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 min-w-0">
                <!-- Company Profile -->
                <div class="qj-detail-card qj-company-detail-card mb-6 min-w-0">
                    <div class="flex items-start gap-6 mb-6">
                        <div class="flex-shrink-0">
                            @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" 
                                     alt="{{ $company->name }}" 
                                     class="w-24 h-24 rounded-lg object-cover">
                            @else
                                <img src="{{ URL::asset('images/favicon/logo.svg') }}" 
                                     alt="Company Logo" 
                                     class="w-24 h-24 rounded-lg">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-3xl font-bold text-black dark:text-white mb-2">
                                {{ $company->name }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4 min-w-0">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $company->city }}, {{ optional($company->country)->name }}
                                </span>
                                <span class="flex items-start gap-1 min-w-0 max-w-full break-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $company->email }}
                                </span>
                                @if($company->phone)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        {{ $company->phone }}
                                    </span>
                                @endif
                            </div>

                            <!-- Contact Actions -->
                            <div class="flex gap-3 items-center flex-wrap">
                                @if($company->website)
                                    <a href="{{ $company->website }}" 
                                       target="_blank"
                                       class="qj-action-button qj-action-button--primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                        Visit Website
                                    </a>
                                @endif
                                
                                @auth
                                    @if(Auth::user()->role == 0)
                                        @if(Auth::user()->isFollowingCompany($company->id))
                                            <form action="{{ route('user.company.unfollow', $company) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-full text-sm font-medium transition duration-200 flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Following
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('user.company.follow', $company) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="qj-action-button qj-action-button--primary">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                    </svg>
                                                    Follow Company
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    <button type="button"
                                            data-modal-target="signin-modal" data-modal-show="signin-modal"
                                            class="qj-action-button qj-action-button--primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                        Log in to Follow
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Company Description -->
                    <div class="qj-company-description mb-6 min-w-0 max-w-full">
                        <h3 class="text-xl font-semibold text-black dark:text-white mb-4">About {{ $company->name }}</h3>
                        <div class="prose prose-sm dark:prose-invert max-w-none min-w-0">
                            {!! nl2br(e($company->description)) !!}
                        </div>
                    </div>

                    <!-- Company Information -->
                    @if($company->address || $company->website || $company->industry)
                        <div class="border-t border-gray-200 dark:border-black pt-6">
                            <h4 class="font-semibold text-black dark:text-white mb-4">Company Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($company->address)
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Address</p>
                                            <p class="text-sm text-black dark:text-white">{{ $company->address }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($company->website)
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Website</p>
                            <a href="{{ $company->website }}" target="_blank" class="qj-inline-link text-sm">{{ $company->website }}</a>
                                        </div>
                                    </div>
                                @endif

                                @if($company->industry)
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Industry</p>
                                            <p class="text-sm text-black dark:text-white">{{ $company->industry }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($company->size)
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Company Size</p>
                                            <p class="text-sm text-black dark:text-white">{{ $company->size }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Company Jobs -->
                <div class="qj-detail-card">
                    <h3 class="text-xl font-semibold text-black dark:text-white mb-6">Open Positions at {{ $company->name }}</h3>
                    
                    @if($jobs->count() > 0)
                        <div class="space-y-4">
                            @foreach($jobs as $job)
                                <div class="qj-detail-row rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-black dark:text-white hover:text-green-700 mb-2">
                                                <a href="{{ route('job.detail', $job->slug) }}">
                                                    {{ $job->title }}
                                                </a>
                                            </h4>
                                            <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                <span>{{ $job->jobType->name ?? 'Full Time' }}</span>
                                                <span>•</span>
                                                <span>{{ $job->experienceLevel->name ?? 'Entry Level' }}</span>
                                                <span>•</span>
                                                <span>{{ $job->location ?? 'Remote' }}</span>
                                            </div>
                                            <p class="text-sm text-gray-700 dark:text-gray-400 line-clamp-2">
                                                {{ Str::limit(strip_tags($job->description), 100) }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2 ml-4">
                                            @if($job->salary)
                                                <span class="text-sm font-medium text-black dark:text-gray-400">
                                                    {{ $job->salary }}
                                                </span>
                                            @endif
                                            <a href="{{ route('job.detail', $job->slug) }}" 
                                               class="qj-inline-link text-sm">
                                                Apply →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center mt-6">
                            {{ $jobs->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400">No open positions at the moment.</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Check back later for new opportunities!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Info Card -->
                <div class="qj-detail-card mb-6 sticky top-4">
                    <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Quick Info</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</p>
                            <p class="text-sm text-black dark:text-white">{{ $company->city }}, {{ optional($company->country)->name }}</p>
                        </div>

                        @if($company->industry)
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Industry</p>
                                <p class="text-sm text-black dark:text-white">{{ $company->industry }}</p>
                            </div>
                        @endif

                        @if($company->size)
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Size</p>
                                <p class="text-sm text-black dark:text-white">{{ $company->size }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Followers</p>
                            <p class="text-sm text-black dark:text-white">{{ $company->followerCount() }} follower{{ $company->followerCount() != 1 ? 's' : '' }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jobs Available</p>
                            <p class="text-sm text-black dark:text-white font-semibold">{{ $jobs->total() }} position{{ $jobs->total() != 1 ? 's' : '' }}</p>
                        </div>
                    </div>

                    <!-- Share Button -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-black">
                        <button class="qj-action-button qj-action-button--secondary w-full">
                            Share Company Profile
                        </button>
                    </div>
                </div>

                <!-- Map -->
                @if($mapEnabled && $mapApiKey && $company->latitude && $company->longitude)
                <div class="qj-detail-card mb-6">
                    <h3 class="text-lg font-semibold text-black dark:text-white mb-3">Location</h3>
                    <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <iframe
                            width="100%"
                            height="200"
                            style="border:0"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed/v1/place?key={{ $mapApiKey }}&q={{ $company->latitude }},{{ $company->longitude }}&zoom=14">
                        </iframe>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $company->address }}</p>
                </div>
                @endif

                <!-- Recent Activity -->
                <div class="qj-detail-card">
                    <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        <div class="text-sm">
                            <p class="text-gray-600 dark:text-gray-400">New job posted 2 days ago</p>
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-600 dark:text-gray-400">Company profile updated 1 week ago</p>
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-600 dark:text-gray-400">Reached 500+ employees this month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Real Content -->
    </div>
</div>
@endsection

@section('js')
<script>
// Show real content, hide skeleton
document.getElementById('company-detail-content').classList.remove('hidden');
document.getElementById('company-detail-skeleton').style.display = 'none';
</script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

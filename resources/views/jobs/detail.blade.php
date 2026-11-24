@extends('app')

@php
    $shareCompanyName = $job->company->name ?? 'BiraConsultant';
    $shareDescription = trim(preg_replace('/\\s+/', ' ', strip_tags($job->description ?? '')));
    $shareDescription = \Illuminate\Support\Str::limit(
        $shareDescription !== '' ? $shareDescription : 'Explore this job opportunity at ' . $shareCompanyName . ' on BiraConsultant.',
        240
    );
    $shareImage = ($job->company && $job->company->logo)
        ? asset('storage/' . ltrim($job->company->logo, '/'))
        : asset('images/favicon/logo.svg');
    $shareCanonicalUrl = route('job.detail', $job->slug);
    $sharePageTitle = $job->title . ' at ' . $shareCompanyName . ' | BiraConsultant';
    $shareLocation = $job->location ?? 'Location not specified';
    $shareJobType = $job->jobType->name ?? 'Job opportunity';
    $shareMetaDescription = $shareCompanyName . ' is hiring: ' . $job->title . '. ' . $shareLocation . '. ' . $shareDescription;
@endphp

@section('title', $sharePageTitle)

@section('meta')
    <meta name="description" content="{{ $shareMetaDescription }}">
    <link rel="canonical" href="{{ $shareCanonicalUrl }}">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="BiraConsultant">
    <meta property="og:title" content="{{ $sharePageTitle }}">
    <meta property="og:description" content="{{ $shareMetaDescription }}">
    <meta property="og:url" content="{{ $shareCanonicalUrl }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:image:alt" content="{{ $shareCompanyName }} company logo">
    <meta property="article:published_time" content="{{ optional($job->created_at)->toIso8601String() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $sharePageTitle }}">
    <meta name="twitter:description" content="{{ $shareMetaDescription }}">
    <meta name="twitter:image" content="{{ $shareImage }}">
    <meta name="twitter:image:alt" content="{{ $shareCompanyName }} company logo">
@endsection

@section('header')
    @include('layouts.header')
@endsection

@section('content')
@include('partials.skeleton-styles')
<div class="qj-detail-page">
    <!-- Skeleton Loading -->
    <div id="job-detail-skeleton">
        <div class="qj-detail-topbar shadow-sm">
            <div class="container mx-auto px-4 py-6">
                <div class="h-6 skeleton-box rounded w-32"></div>
            </div>
        </div>
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    @include('components.skeleton-job-detail')
                </div>
                <div class="lg:col-span-1">
                    @include('components.skeleton-job-sidebar')
                </div>
            </div>
        </div>
    </div>

    <!-- Real Content (hidden initially) -->
    <div id="job-detail-content" class="hidden">
    <div class="qj-detail-topbar shadow-sm">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('jobs') }}" class="qj-inline-link flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Jobs
                </a>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Posted {{ $job->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @include('partials.adsense', ['placement' => 'job_detail'])
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Job Title and Company -->
                <div class="qj-detail-card mb-6">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="flex-shrink-0">
                            @if($job->company && $job->company->logo)
                                <img src="{{ asset('storage/' . $job->company->logo) }}"
                                     alt="{{ $job->company->name }}"
                                     class="w-20 h-20 rounded-lg object-cover">
                            @else
                                <div class="w-20 h-20 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-500 dark:text-gray-300">
                                        {{ substr($job->company->name ?? 'C', 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-black dark:text-white mb-2">
                                {{ $job->title }}
                            </h1>
                            <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-black dark:text-white">{{ $job->company->name ?? 'Company' }}</span>
                                <span>•</span>
                                <span>{{ $job->jobType->name ?? 'Full Time' }}</span>
                                <span>•</span>
                                <span>{{ $job->experienceLevel->name ?? 'Entry Level' }}</span>
                            </div>
                        </div>
                    </div>

                    @php
                        $shareUrl = urlencode(route('job.detail', $job->slug));
                        $shareTitle = urlencode($job->title . ' - ' . ($job->company->name ?? 'BiraConsultant'));
                        $shareText = urlencode('Check out this job opportunity: ' . $job->title . ' - ' . route('job.detail', $job->slug));
                    @endphp

                    <!-- Share Job -->
                    <div class="mb-6 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <h3 class="text-base font-semibold text-black dark:text-white mb-3">Share this Job</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Share job on LinkedIn" class="flex items-center justify-center gap-2 rounded-full bg-[#0A66C2] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                                <svg aria-hidden="true" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.13 1.44-2.13 2.94v5.67H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.26 2.37 4.26 5.45v6.29ZM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12ZM7.12 20.45H3.56V9h3.56v11.45ZM22.22 0H1.78C.8 0 0 .77 0 1.72v20.56C0 23.23.8 24 1.78 24h20.44c.98 0 1.78-.77 1.78-1.72V1.72C24 .77 23.2 0 22.22 0Z"/></svg>
                                <span>LinkedIn</span>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Share job on Facebook" class="flex items-center justify-center gap-2 rounded-full bg-[#1877F2] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                                <svg aria-hidden="true" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.4c0-3.03 1.79-4.7 4.54-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.95.93-1.95 1.88v2.27h3.32l-.53 3.49h-2.79V24C19.61 23.1 24 18.1 24 12.07Z"/></svg>
                                <span>Facebook</span>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener noreferrer" aria-label="Share job on X" class="flex items-center justify-center gap-2 rounded-full bg-black px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                                <svg aria-hidden="true" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-6.78 7.75L23.2 22h-6.25l-4.9-8.02L5.03 22H1.9l7.25-8.29L1.4 2h6.4l4.43 7.37L18.9 2Zm-1.1 18h1.73L6.87 3.9H5.01L17.8 20Z"/></svg>
                                <span>X</span>
                            </a>
                            <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener noreferrer" aria-label="Share job on WhatsApp" class="flex items-center justify-center gap-2 rounded-full bg-[#25D366] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                                <svg aria-hidden="true" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.86 11.86 0 0 0 12.08 0C5.5 0 .15 5.35.15 11.93c0 2.1.55 4.15 1.6 5.96L.05 24l6.27-1.64a11.9 11.9 0 0 0 5.76 1.47h.01c6.58 0 11.93-5.35 11.93-11.93 0-3.19-1.24-6.19-3.5-8.42ZM12.09 21.8a9.9 9.9 0 0 1-5.04-1.38l-.36-.21-3.72.97.99-3.63-.24-.37a9.86 9.86 0 0 1-1.52-5.25c0-5.46 4.44-9.9 9.9-9.9a9.83 9.83 0 0 1 7 2.9 9.83 9.83 0 0 1 2.9 7c0 5.46-4.44 9.9-9.9 9.9Zm5.43-7.42c-.3-.15-1.77-.87-2.05-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.47-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49s1.07 2.89 1.22 3.09c.15.2 2.1 3.2 5.08 4.49.71.31 1.27.5 1.7.64.71.23 1.36.2 1.87.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35Z"/></svg>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                    <!-- Job Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-black dark:text-white">{{ $job->location ?? 'Remote' }}</span>
                        </div>

                        @if($job->salary)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-black dark:text-white font-medium">{{ $job->salary }}</span>
                            </div>
                        @endif

                        @if($job->deadline)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 qj-job-deadline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="qj-job-deadline font-medium">
                                    Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="text-black dark:text-white">
                                {{ $job->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-black dark:text-white mb-4">Job Description</h3>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    <!-- Requirements -->
                    @if($job->requirements)
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold text-black dark:text-white mb-4">Requirements</h3>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- How to Apply -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-black dark:text-white mb-4">How to Apply</h3>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            {!! nl2br(e($job->how_to_apply ?? 'Please click the Apply Now button below to submit your application.')) !!}
                        </div>
                    </div>
                </div>

                <!-- Similar Jobs -->
                @if($similarJobs->count() > 0)
                <div class="qj-detail-card">
                    <h3 class="text-xl font-semibold text-black dark:text-white mb-4">Similar Jobs</h3>
                    <div class="space-y-4">
                        @foreach($similarJobs as $similarJob)
                            <div class="qj-detail-row rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-black dark:text-white hover:text-green-700">
                                            <a href="{{ route('job.detail', $similarJob->slug) }}">
                                                {{ $similarJob->title }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $similarJob->company->name ?? 'Company' }} • {{ $similarJob->location ?? 'Remote' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('job.detail', $similarJob->slug) }}"
                                       class="qj-inline-link text-sm">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Job Application -->
                <div class="qj-detail-card mb-6 sticky top-4">
                    @if($hasApplied)
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-black dark:text-white mb-2">Already Applied</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">You have already applied for this position.</p>
                            <a href="{{ route('user.applications') }}" class="qj-inline-link font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                View My Applications
                            </a>
                        </div>
                    @else
                        <h3 class="text-xl font-semibold text-black dark:text-white mb-4">Apply for this Job</h3>

                        @auth
                            <form method="POST" action="{{ route('user.apply', $job->id) }}" enctype="multipart/form-data" id="applyForm">
                                @csrf

                                <!-- Cover Letter (Required) -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Cover Letter <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="cover_letter"
                                              rows="5"
                                              placeholder="Tell us why you're the perfect fit for this role..."
                                              class="qj-content-form-control qj-content-form-control--textarea text-sm"
                                              required minlength="50" maxlength="5000">{{ old('cover_letter') }}</textarea>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 50 characters</p>
                                    @error('cover_letter')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CV Upload (Optional) -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Upload CV
                                    </label>
                                    <div class="relative">
                                        <input type="file" name="cv" id="cv_upload"
                                               accept=".pdf,.doc,.docx"
                                               class="qj-modal-file-control block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium dark:file:bg-gray-700 dark:file:text-gray-300">
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PDF, DOC, DOCX — Max 2MB</p>
                                    @error('cv')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Resume Upload (Optional) -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Upload Resume
                                    </label>
                                    <div class="relative">
                                        <input type="file" name="resume" id="resume_upload"
                                               accept=".pdf,.doc,.docx"
                                               class="qj-modal-file-control block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium dark:file:bg-gray-700 dark:file:text-gray-300">
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PDF, DOC, DOCX — Max 2MB</p>
                                    @error('resume')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Cover Letter File (Optional) -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Cover Letter as File (Optional)
                                    </label>
                                    <div class="relative">
                                        <input type="file" name="cover_letter_file" id="cover_letter_file_upload"
                                               accept=".pdf,.doc,.docx"
                                               class="qj-modal-file-control block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium dark:file:bg-gray-700 dark:file:text-gray-300">
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PDF, DOC, DOCX — Max 2MB</p>
                                    @error('cover_letter_file')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="qj-apply-note rounded-lg p-3 mb-4">
                                    <p class="text-xs">
                                        <strong>Note:</strong> By submitting this application, you confirm that all information provided is accurate. The company will review your profile and contact you if shortlisted.
                                    </p>
                                </div>

                                @include('components.captcha')

                                <button type="submit"
                                        id="submitBtn"
                                        class="qj-action-button qj-action-button--primary w-full py-3 px-6 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Submit Application
                                </button>
                            </form>
                        @else
                            <div class="text-center">
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    Please log in to apply for this job.
                                </p>
                                <button type="button"
                                        data-modal-target="signin-modal" data-modal-show="signin-modal"
                                        class="qj-action-button qj-action-button--primary cursor-pointer">
                                    Login / Register
                                </button>
                            </div>
                        @endauth
                    @endif

                    <!-- Company Info -->
                    @if($job->company)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-black dark:text-white mb-3">{{ $job->company->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ Str::limit(strip_tags($job->company->description ?? ''), 150) }}
                        </p>
                        @if($job->company->website)
                            <a href="{{ $job->company->website }}"
                               target="_blank"
                               class="qj-inline-link text-sm">
                                Visit Website
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Real Content -->
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('js')
<script>
// Show real content, hide skeleton
document.getElementById('job-detail-content').classList.remove('hidden');
document.getElementById('job-detail-skeleton').style.display = 'none';

// Form submit loading state
document.getElementById('applyForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Submitting...
    `;
    btn.classList.add('opacity-75', 'cursor-not-allowed');
});
</script>
@endsection

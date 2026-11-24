@extends('app')

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

@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-dashboard-page">
    <!-- Dashboard Header -->
    <div class="qj-dashboard-hero">
        <div class="qj-dashboard-hero__inner">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-1">{{ $company->name }}</h1>
                    <p class="text-sm opacity-75">Company Dashboard</p>
                </div>
                <div class="text-right">
                    <div class="text-xs opacity-75 mb-1">Verification</div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $company->verification_status_color }}">
                        {{ $company->verification_status_label }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">

        <!-- Verification Alerts -->
        @if($company->verification_status == 0 && !$company->registration_doc_path)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400 dark:border-yellow-600 rounded-lg p-4 mb-6 flex items-center gap-3">
            <svg class="w-6 h-6  flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Company verification pending</p>
                <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Please <a href="{{ route('company.verification') }}" class="underline font-medium">submit your verification documents</a> to start posting jobs.</p>
            </div>
        </div>
        @elseif($company->verification_status == 2)
        <div class="bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 rounded-lg p-4 mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-red-800 dark:text-red-300">Verification rejected</p>
                <p class="text-xs text-red-700 dark:text-red-400 mt-1">Reason: {{ $company->verification_rejection_reason ?? 'Documents were not acceptable.' }} Please <a href="{{ route('company.verification') }}" class="underline font-medium">resubmit</a>.</p>
            </div>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="qj-dashboard-stats-grid qj-dashboard-stats-grid--company mb-8">
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-black dark:text-white">{{ $stats['total_jobs'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Total Jobs</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-green-600">{{ $stats['active_jobs'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Active</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold ">{{ $stats['pending_jobs'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Pending</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-blue-600">{{ $stats['total_applications'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Applicants</div>
            </div>
            <div class="qj-dashboard-stat">
                <div class="text-xl font-bold text-emerald-600">{{ $stats['hired'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Hired</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main: Recent Applications + Jobs -->
            <div class="lg:col-span-2">
                <!-- Job Postings -->
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md">
                    <div class="flex flex-wrap items-center justify-between gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-black dark:text-white">Your Job Postings</h2>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('company.jobs.import') }}" class="px-4 py-2 bg-green-100 hover:bg-green-200 text-green-900 text-sm font-semibold rounded-full transition dark:bg-green-900/40 dark:text-green-200 dark:hover:bg-green-900/60">
                                Upload spreadsheet
                            </a>
                            <a href="{{ route('company.jobs.create') }}" class="px-4 py-2 bg-yellow-300 hover:bg-yellow-400 text-black text-sm font-semibold rounded-full transition">
                                + Post New Job
                            </a>
                        </div>
                    </div>

                    @if($jobs->count() > 0)
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($jobs as $job)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-medium text-black dark:text-white text-sm">{{ $job->title }}</h3>
                                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $job->approvalStatusColor() }}">
                                                {{ $job->approvalStatusLabel() }}
                                            </span>
                                            @if($job->isExpired())
                                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium text-gray-600 bg-gray-200">Expired</span>
                                            @elseif($job->status && $job->approval_status === 'approved')
                                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium text-green-600 bg-green-100">Live</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ $job->location ?? 'No location' }}</span>
                                            <span>•</span>
                                            <span>{{ $job->applications_count ?? 0 }} application{{ ($job->applications_count ?? 0) != 1 ? 's' : '' }}</span>
                                            <span>•</span>
                                            <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                                            @if($job->deadline)
                                                <span>•</span>
                                                <span class="{{ $job->isExpired() ? 'text-red-500' : '' }}">Deadline: {{ $job->deadline->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="{{ route('company.jobs.edit', $job->id) }}" class="text-xs text-yellow-500 hover:text-yellow-600 font-medium">Edit</a>
                                        @if($job->approval_status === 'approved')
                                        <form action="{{ route('company.jobs.toggle', $job->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs {{ $job->status ? 'text-red-500' : 'text-green-500' }} hover:underline font-medium">
                                                {{ $job->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('company.job-applications', $job->id) }}" class="text-xs bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 px-3 py-1.5 rounded-md font-medium hover:bg-yellow-100 dark:hover:bg-yellow-900/50 transition">
                                            Applicants
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-3">No job postings yet.</p>
                            <a href="{{ route('company.jobs.create') }}" class="inline-block px-4 py-2 bg-yellow-300 hover:bg-yellow-400 text-black text-sm font-semibold rounded-full transition">
                                Post Your First Job
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Recent Applications -->
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md mt-6">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-black dark:text-white">Recent Applications</h2>
                        <a href="{{ route('company.applications') }}" class="text-sm text-yellow-500 hover:text-yellow-600 font-medium">View All →</a>
                    </div>

                    @if($recentApplications->count() > 0)
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentApplications as $application)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3 flex-1 min-w-0">
                                        <div class="flex-shrink-0">
                                            @if($application->user && $application->user->image)
                                                <img src="{{ asset('storage/' . $application->user->image) }}" alt="" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-gray-500">{{ substr(optional($application->user)->name ?? '?', 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-medium text-black dark:text-white text-sm truncate">{{ optional($application->user)->name ?? 'Unknown' }}</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                Applied for: <span class="">{{ $application->job->title ?? 'Unknown Job' }}</span>
                                            </p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $application->status->color_class ?? 'bg-gray-100 text-gray-700' }}">
                                                    {{ $application->status->label ?? 'Unknown' }}
                                                </span>
                                                <span class="text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('company.job-applications', $application->job_id) }}" class="text-xs text-yellow-500 hover:text-yellow-600 flex-shrink-0">
                                        View
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No applications yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-4 mb-6">
                    <h3 class="text-base font-semibold text-black dark:text-white mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('company.jobs.create') }}" class="flex items-center gap-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/40 transition group">
                            <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:">Post New Job</span>
                        </a>
                        <a href="{{ route('company.applications') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition group">
                            <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:">All Applications</span>
                        </a>
                        <a href="{{ route('company.verification') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition group">
                            <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:">Verification</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-4">
                    <h3 class="text-base font-semibold text-black dark:text-white mb-3">Company Info</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Industry</span>
                            <span class="text-black dark:text-white">{{ $company->industry ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Location</span>
                            <span class="text-black dark:text-white">{{ $company->city ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Size</span>
                            <span class="text-black dark:text-white">{{ $company->size ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Member since</span>
                            <span class="text-black dark:text-white">{{ $company->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-dashboard-page min-h-screen bg-gray-50 dark:bg-black">
    <!-- Header -->
    <div class="bg-white dark:bg-custom-dark shadow-sm">
        <div class="container mx-auto px-4 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('company.applications') }}" class="flex items-center text-yellow-500 hover:text-yellow-600 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        All Applications
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-black dark:text-white">{{ $job->title }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $applications->total() }} applicant{{ $applications->total() != 1 ? 's' : '' }}</p>
                    </div>
                </div>
                <a href="{{ route('job.detail', $job->slug) }}" class="text-sm text-gray-500 dark:text-gray-400 hover:">
                    View Job Post →
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">

        <!-- Filter by Status -->
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('company.job-applications', $job->id) }}"
               class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ !request('status') ? 'bg-yellow-300 text-black' : 'bg-gray-100 text-black dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                All
            </a>
            @foreach($statuses as $status)
                <a href="{{ route('company.job-applications', ['job' => $job->id, 'status' => $status->id]) }}"
                   class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == $status->id ? 'bg-yellow-300 text-black' : 'bg-gray-100 text-black dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    {{ $status->label }}
                    <span class="ml-1 text-xs opacity-75">({{ $applications->where('status_id', $status->id)->count() }})</span>
                </a>
            @endforeach
        </div>

        <!-- Applications -->
        @if($applications->count() > 0)
            <div class="space-y-4">
                @foreach($applications as $application)
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md">
                    <div class="p-5">
                        <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                            <!-- Applicant Info -->
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <div class="flex-shrink-0">
                                    @if($application->user && $application->user->image)
                                        <img src="{{ asset('storage/' . $application->user->image) }}" alt="" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <span class="text-lg font-bold text-gray-500 dark:text-gray-400">{{ substr(optional($application->user)->name ?? '?', 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="font-semibold text-black dark:text-white">{{ optional($application->user)->name ?? 'Unknown' }}</h3>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $application->status->color_class ?? 'bg-gray-100 text-gray-700' }}" id="status-badge-{{ $application->id }}">
                                            {{ $application->status->label ?? 'Unknown' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ optional($application->user)->email ?? 'No email' }}</p>
                                    @if(optional($application->user)->phone)
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">📞 {{ $application->user->phone }}</p>
                                    @endif
                                    @if(optional($application->user)->country)
                                        <p class="text-xs text-gray-400 dark:text-gray-500">📍 {{ $application->user->country->name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Applied {{ $application->created_at->format('M d, Y \\a\\t h:i A') }}</p>
                                    @if($application->reviewed_at)
                                        <p class="text-xs text-gray-400 dark:text-gray-500">Reviewed {{ $application->reviewed_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Stage Update -->
                            <div class="flex-shrink-0 lg:w-56 space-y-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Update Stage</label>
                                    <select onchange="updateStage({{ $application->id }}, this.value)"
                                            class="w-full px-3 py-2 rounded-full text-sm focus:ring-2 focus:ring-yellow-300 bg-gray-100 text-black dark:bg-black dark:text-white">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ $application->status_id == $status->id ? 'selected' : '' }}>{{ $status->label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Stage Pipeline Visual -->
                                <div class="bg-gray-50 dark:bg-black rounded-md p-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1.5">Pipeline</p>
                                    <div class="flex items-center gap-1">
                                        @php
                                            $currentStage = $application->status_id;
                                            $stages = [
                                                1 => 'Received',
                                                2 => 'Review',
                                                3 => 'Interview',
                                                4 => 'Offer',
                                                5 => 'Hired',
                                            ];
                                            $isRejected = $currentStage == 6;
                                        @endphp
                                        @foreach($stages as $sid => $slabel)
                                            @php
                                                $isActive = !$isRejected && $currentStage >= $sid;
                                                $isCurrent = $currentStage == $sid;
                                            @endphp
                                            <div class="flex-1 text-center">
                                                <div class="w-full h-1.5 rounded-full {{ $isActive ? ($isCurrent ? 'bg-yellow-400' : 'bg-green-400') : 'bg-gray-200 dark:bg-gray-600' }}"></div>
                                                <p class="text-[9px] mt-0.5 {{ $isCurrent ? 'text-yellow-600 dark:text-yellow-300 font-medium' : ($isActive ? 'text-green-600 dark:text-green-400' : 'text-gray-400') }}">{{ $slabel }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($isRejected)
                                        <div class="mt-1 text-center">
                                            <span class="inline-block px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded text-xs font-medium">Rejected</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Cover Letter -->
                        @if($application->cover_letter)
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <button onclick="toggleCoverLetter({{ $application->id }})" class="text-xs text-yellow-500 hover:text-yellow-600 font-medium focus:outline-none">
                                <span id="cl-toggle-{{ $application->id }}">Show Cover Letter ▾</span>
                            </button>
                            <div id="cl-content-{{ $application->id }}" class="hidden mt-2">
                                <div class="bg-gray-50 dark:bg-black rounded-md p-3 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line max-h-40 overflow-y-auto">
                                    {{ $application->cover_letter }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Attached Files -->
                        @if($application->cv_path || $application->resume_path || $application->cover_letter_path)
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Attached Files:</p>
                            <div class="flex flex-wrap gap-2">
                                @if($application->cv_path)
                                    <a href="{{ route('company.application.download', [$application, 'cv']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-full text-xs font-medium hover:bg-blue-100 dark:hover:bg-blue-900/40 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Download CV
                                    </a>
                                @endif
                                @if($application->resume_path)
                                    <a href="{{ route('company.application.download', [$application, 'resume']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-full text-xs font-medium hover:bg-green-100 dark:hover:bg-green-900/40 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Download Resume
                                    </a>
                                @endif
                                @if($application->cover_letter_path)
                                    <a href="{{ route('company.application.download', [$application, 'cover_letter']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 rounded-full text-xs font-medium hover:bg-purple-100 dark:hover:bg-purple-900/40 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Cover Letter File
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $applications->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-semibold text-black dark:text-white mb-2">No applications yet</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">No candidates have applied to this job posting yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('js')
<script>
function toggleCoverLetter(id) {
    const content = document.getElementById('cl-content-' + id);
    const toggle = document.getElementById('cl-toggle-' + id);
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        toggle.textContent = 'Hide Cover Letter ▴';
    } else {
        content.classList.add('hidden');
        toggle.textContent = 'Show Cover Letter ▾';
    }
}

function updateStage(applicationId, statusId) {
    if (!confirm('Are you sure you want to update this application\'s status?')) {
        location.reload();
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(`/company/application/${applicationId}/stage`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token || '',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status_id: parseInt(statusId) })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById('status-badge-' + applicationId);
            if (badge) {
                badge.textContent = data.status_label;
                badge.className = 'inline-block px-2 py-0.5 rounded-full text-xs font-medium ' + data.status_color;
            }
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50';
            toast.textContent = data.message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
            // Reload to update pipeline visual
            setTimeout(() => location.reload(), 1000);
        } else {
            alert('Failed to update status.');
            location.reload();
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred. Please try again.');
        location.reload();
    });
}
</script>
@endsection

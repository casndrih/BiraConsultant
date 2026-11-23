@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-dashboard-page">
    <!-- Header -->
    <div class="qj-detail-topbar shadow-sm">
        <div class="container mx-auto px-4 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center text-yellow-500 hover:text-yellow-600 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Dashboard
                    </a>
                    <h1 class="text-xl font-bold text-black dark:text-white">My Applications</h1>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $applications->total() }} total
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">

        <!-- Filter Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
            <div class="flex flex-wrap border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('user.applications') }}"
                   class="px-5 py-3 text-sm font-medium transition {{ !request('status') ? 'text-yellow-500 border-b-2 border-yellow-500' : 'text-gray-500 dark:text-gray-400 hover:text-yellow-500' }}">
                    All
                </a>
                <a href="{{ route('user.applications', ['status' => 'pending']) }}"
                   class="px-5 py-3 text-sm font-medium transition {{ request('status') === 'pending' ? 'text-yellow-500 border-b-2 border-yellow-500' : 'text-gray-500 dark:text-gray-400 hover:text-yellow-500' }}">
                    Received
                </a>
                <a href="{{ route('user.applications', ['status' => 'reviewing']) }}"
                   class="px-5 py-3 text-sm font-medium transition {{ request('status') === 'reviewing' ? 'text-yellow-500 border-b-2 border-yellow-500' : 'text-gray-500 dark:text-gray-400 hover:text-yellow-500' }}">
                    Under Review
                </a>
                <a href="{{ route('user.applications', ['status' => 'interview']) }}"
                   class="px-5 py-3 text-sm font-medium transition {{ request('status') === 'interview' ? 'text-yellow-500 border-b-2 border-yellow-500' : 'text-gray-500 dark:text-gray-400 hover:text-yellow-500' }}">
                    Interview
                </a>
                <a href="{{ route('user.applications', ['status' => 'accepted']) }}"
                   class="px-5 py-3 text-sm font-medium transition {{ request('status') === 'accepted' ? 'text-yellow-500 border-b-2 border-yellow-500' : 'text-gray-500 dark:text-gray-400 hover:text-yellow-500' }}">
                    Job Offer
                </a>
                <a href="{{ route('user.applications', ['status' => 'rejected']) }}"
                   class="px-5 py-3 text-sm font-medium transition {{ request('status') === 'rejected' ? 'text-yellow-500 border-b-2 border-yellow-500' : 'text-gray-500 dark:text-gray-400 hover:text-yellow-500' }}">
                    Rejected
                </a>
            </div>
        </div>

        <!-- Applications List -->
        <div class="space-y-4">
            @if($applications->count() > 0)
                @foreach($applications as $application)
                @php
                    $stages = [
                        1 => ['label' => 'Received', 'icon' => '📨'],
                        2 => ['label' => 'Under Review', 'icon' => '🔍'],
                        3 => ['label' => 'Interview', 'icon' => '🎤'],
                        4 => ['label' => 'Job Offer', 'icon' => '🎉'],
                        5 => ['label' => 'Hired', 'icon' => '✅'],
                    ];
                    $currentStage = $application->status_id;
                    $isRejected = $currentStage == 6;
                    $isHired = $currentStage == 5;
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-5">
                        <!-- Stage Pipeline -->
                        <div class="mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                @foreach($stages as $sid => $stage)
                                    @php
                                        $isActive = !$isRejected && $currentStage >= $sid;
                                        $isCurrent = $currentStage == $sid;
                                    @endphp
                                    <div class="flex-1 flex items-center">
                                        <div class="flex flex-col items-center flex-1">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $isCurrent ? 'bg-yellow-300 text-black ring-4 ring-yellow-100 dark:ring-yellow-900/30' : ($isActive ? 'bg-green-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-400') }}">
                                                @if($isActive && !$isCurrent)
                                                    ✓
                                                @else
                                                    {{ $stage['icon'] }}
                                                @endif
                                            </div>
                                            <span class="text-[10px] mt-1 font-medium {{ $isCurrent ? 'text-yellow-600 dark:text-yellow-400' : ($isActive ? 'text-green-600 dark:text-green-400' : 'text-gray-400') }}">
                                                {{ $stage['label'] }}
                                            </span>
                                        </div>
                                        @if(!$loop->last)
                                            <div class="h-0.5 flex-1 mx-1 {{ $isActive && $currentStage > $sid ? 'bg-green-400' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($isRejected)
                                <div class="mt-2 text-center">
                                    <span class="inline-block px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-medium">Application Rejected</span>
                                </div>
                            @elseif($isHired)
                                <div class="mt-2 text-center">
                                    <span class="inline-block px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-xs font-medium">🎉 Congratulations! You've been hired!</span>
                                </div>
                            @endif
                        </div>

                        <!-- Job Info -->
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="flex items-start gap-3 flex-1">
                                <div class="flex-shrink-0">
                                    @if($application->job->company && $application->job->company->logo)
                                        <img src="{{ asset('storage/' . $application->job->company->logo) }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <span class="text-sm font-bold text-gray-500">{{ substr(optional($application->job->company)->name ?? 'C', 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-black dark:text-white">
                                        <a href="{{ route('job.detail', $application->job->slug) }}" class="hover:text-yellow-500">
                                            {{ $application->job->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ optional($application->job->company)->name }} • {{ $application->job->location ?? 'Remote' }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            Applied {{ $application->created_at->format('M d, Y') }}
                                        </span>
                                        @if($application->reviewed_at)
                                            <span class="text-xs text-gray-400">• Reviewed {{ $application->reviewed_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <a href="{{ route('job.detail', $application->job->slug) }}" class="text-yellow-500 hover:text-yellow-600 text-sm font-medium">
                                    View Job
                                </a>
                                @if($application->status_id == 1)
                                    <form method="POST" action="{{ route('user.application.withdraw', $application->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to withdraw this application? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                            Withdraw
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="flex justify-center mt-6">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-black dark:text-white mb-2">No applications found</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">
                        @if(request('status'))
                            You don't have any <span class="font-medium">{{ request('status') }}</span> applications.
                        @else
                            You haven't applied to any jobs yet. Start browsing and find your perfect opportunity!
                        @endif
                    </p>
                    <a href="{{ route('jobs') }}" class="inline-flex items-center gap-2 bg-yellow-300 hover:bg-yellow-400 text-black font-medium py-2.5 px-6 rounded-lg transition">
                        Browse Jobs
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
@include('partials.skeleton-styles')
<div class="bc-dashboard-page">
    <!-- Header -->
    <div class="bc-detail-topbar shadow-sm">
        <div class="container mx-auto px-4 py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('company.dashboard') }}" class="flex items-center text-yellow-500 hover:text-yellow-600 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Dashboard
                    </a>
                    <h1 class="text-xl font-bold text-black dark:text-white">All Applications</h1>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400" id="total-count">
                    Loading...
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">

        <!-- Filters -->
        <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="{{ route('company.applications') }}" id="filter-form" class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by name or email..."
                           class="w-full px-3 py-2 rounded-full text-sm focus:ring-2 focus:ring-yellow-300 bg-gray-100 text-black dark:bg-black dark:text-white dark:placeholder-gray-400">
                </div>
                <div>
                    <select name="job_id" class="px-3 py-2 rounded-full text-sm focus:ring-2 focus:ring-yellow-300 bg-gray-100 text-black dark:bg-black dark:text-white">
                        <option value="">All Jobs</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" class="px-3 py-2 rounded-full text-sm focus:ring-2 focus:ring-yellow-300 bg-gray-100 text-black dark:bg-black dark:text-white">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-yellow-300 hover:bg-yellow-400 text-black font-medium rounded-full text-sm transition">
                    Filter
                </button>
                <a href="{{ route('company.applications') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition inline-flex items-center">
                    Clear
                </a>
            </form>
        </div>

        <!-- Applications List (skeleton initially) -->
        <div class="space-y-4" id="applications-container">
            @for ($i = 0; $i < 5; $i++)
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md">
                <div class="p-5">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <div class="w-12 h-12 rounded-full skeleton-box flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="h-5 skeleton-box rounded w-32"></div>
                                    <div class="h-5 w-20 skeleton-box rounded-full"></div>
                                </div>
                                <div class="h-3 skeleton-box rounded w-48 mt-1.5"></div>
                                <div class="flex items-center gap-3 mt-1">
                                    <div class="h-3 skeleton-box rounded w-40"></div>
                                    <div class="h-3 skeleton-box rounded w-24"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 lg:w-56">
                            <div class="h-3 skeleton-box rounded w-24 mb-1"></div>
                            <div class="h-8 skeleton-box rounded w-full"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>

        <!-- Pagination -->
        <div class="mt-6" id="pagination-container">
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show skeleton cards
    function showSkeletons() {
        const container = document.getElementById('applications-container');
        container.innerHTML = '';
        for (let i = 0; i < 5; i++) {
            container.innerHTML += `<div class="bg-white dark:bg-custom-dark rounded-lg shadow-md">
                <div class="p-5">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <div class="w-12 h-12 rounded-full skeleton-box flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="h-5 skeleton-box rounded w-32"></div>
                                    <div class="h-5 w-20 skeleton-box rounded-full"></div>
                                </div>
                                <div class="h-3 skeleton-box rounded w-48 mt-1.5"></div>
                                <div class="flex items-center gap-3 mt-1">
                                    <div class="h-3 skeleton-box rounded w-40"></div>
                                    <div class="h-3 skeleton-box rounded w-24"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 lg:w-56">
                            <div class="h-3 skeleton-box rounded w-24 mb-1"></div>
                            <div class="h-8 skeleton-box rounded w-full"></div>
                        </div>
                    </div>
                </div>
            </div>`;
        }
        document.getElementById('total-count').textContent = 'Loading...';
        document.getElementById('pagination-container').innerHTML = '';
    }

    // Load applications via AJAX
    function loadApplications(params) {
        showSkeletons();
        const queryString = params ? '?' + params : '';
        fetch('/company/applications/list' + queryString, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                document.getElementById('applications-container').innerHTML = data.html;
                document.getElementById('total-count').textContent = data.total + ' total';
                document.getElementById('pagination-container').innerHTML = data.pagination;
                attachPaginationHandlers();
            })
            .catch(() => {
                document.getElementById('applications-container').innerHTML = '<div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-12 text-center"><p class="text-red-500">Failed to load applications. Please try again.</p></div>';
            });
    }

    // Pagination handlers
    function attachPaginationHandlers() {
        document.querySelectorAll('#pagination-container a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                loadApplications(url.searchParams.toString());
            });
        });
    }

    // Filter form submit -> AJAX load
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(this)).toString();
            loadApplications(params);
        });
    }

    // Clear filters
    document.querySelectorAll('a[href="{{ route('company.applications') }}"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadApplications('');
        });
    });

    // Initial load
    loadApplications('');
});

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
        } else {
            alert('Failed to update status.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred. Please try again.');
    });
}
</script>
@endsection

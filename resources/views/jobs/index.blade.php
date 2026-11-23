@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
@include('partials.skeleton-styles')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <section class="qj-search-hero qj-search-hero--jobs">
        <div class="qj-search-hero__inner">
            <span class="qj-search-hero__eyebrow">Make your next move</span>
            <h1 class="qj-search-hero__title">Find work that fits your life.</h1>
            <p class="qj-search-hero__subtitle">Explore opportunities from employers across Papua New Guinea.</p>

            <form method="GET" action="{{ route('jobs') }}" class="qj-search-panel" id="search-form">
                <div class="qj-search-fields qj-search-fields--jobs">
                    <label class="qj-search-field">
                        <span>Role or company</span>
                        <span class="qj-search-input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="10.8" cy="10.8" r="6.8" />
                                <path stroke-linecap="round" d="m16 16 4.2 4.2" />
                            </svg>
                            <input type="text" name="search" placeholder="e.g. Project Manager" value="{{ request('search') }}" class="qj-search-input">
                        </span>
                    </label>
                    <label class="qj-search-field">
                        <span>Location</span>
                        <span class="qj-search-input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.2c0 5.2-7.5 11-7.5 11s-7.5-5.8-7.5-11a7.5 7.5 0 1 1 15 0Z" />
                                <circle cx="12" cy="10" r="2.4" />
                            </svg>
                            <input type="text" name="location" placeholder="City or province" value="{{ request('location') }}" class="qj-search-input">
                        </span>
                    </label>
                    <button type="submit" class="qj-search-submit">
                        Search jobs
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h11m-4-4 4 4-4 4" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Filters Section -->
    <div class="container mx-auto px-4 py-8 qj-results-container">
        @include('partials.adsense', ['placement' => 'jobs'])
        <div class="qj-filter-panel mb-8">
            <div class="qj-filter-panel__heading">
                <div>
                    <h3>Refine your search</h3>
                    <p>Choose the details that matter to you.</p>
                </div>
                <span class="qj-filter-panel__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M7 12h10m-7 6h4" />
                    </svg>
                </span>
            </div>
            <form method="GET" action="{{ route('jobs') }}" id="filter-form" class="qj-filter-grid">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="location" value="{{ request('location') }}">
                <!-- Category Filter -->
                <div>
                    <label class="qj-filter-label">Category</label>
                    <select name="category" class="qj-filter-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Job Type Filter -->
                <div>
                    <label class="qj-filter-label">Job type</label>
                    <select name="type" class="qj-filter-select">
                        <option value="">All Types</option>
                        @foreach($jobTypes as $type)
                            <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Experience Level Filter -->
                <div>
                    <label class="qj-filter-label">Experience level</label>
                    <select name="experience" class="qj-filter-select">
                        <option value="">All Levels</option>
                        @foreach($experienceLevels as $level)
                            <option value="{{ $level->id }}" {{ request('experience') == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Clear Filters Button -->
                <div class="qj-filter-clear-cell">
                    <a href="{{ route('jobs') }}" class="qj-clear-filters">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Info -->
        <div class="qj-results-heading mb-6" id="results-info">
            <h2 id="results-count">
                Loading...
            </h2>
            <div id="results-range">
                &nbsp;
            </div>
        </div>

        <!-- Jobs Grid (skeleton initially) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="jobs-container">
            @for ($i = 0; $i < 9; $i++)
                @include('components.skeleton-card')
            @endfor
        </div>

        <!-- Pagination -->
        <div class="flex justify-center" id="pagination-container">
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
    // Load jobs via AJAX
    function loadJobs(params) {
        // Show skeleton
        const container = document.getElementById('jobs-container');
        container.innerHTML = '';
        for (let i = 0; i < 9; i++) {
            container.innerHTML += `<div class="bg-white dark:bg-custom-dark rounded-lg shadow-sm p-6 flex flex-col justify-between w-full">
                <div class="flex items-start gap-4 mb-3">
                    <div class="w-12 h-12 rounded-md skeleton-box flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <div class="h-4 skeleton-box rounded w-3/4 mb-2"></div>
                        <div class="h-3 skeleton-box rounded w-1/2 mb-2"></div>
                        <div class="h-3 skeleton-box rounded w-2/3"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="h-3 skeleton-box rounded w-full mb-1.5"></div>
                    <div class="h-3 skeleton-box rounded w-5/6 mb-1.5"></div>
                    <div class="h-3 skeleton-box rounded w-4/6"></div>
                </div>
                <div class="flex items-center justify-between mt-auto">
                    <div class="h-5 w-16 skeleton-box rounded-full"></div>
                    <div class="h-8 w-24 skeleton-box rounded-full"></div>
                </div>
            </div>`;
        }
        document.getElementById('results-count').textContent = 'Loading...';
        document.getElementById('results-range').innerHTML = '&nbsp;';
        document.getElementById('pagination-container').innerHTML = '';

        const queryString = params ? '?' + params : '';
        fetch('/jobs/list' + queryString, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                container.innerHTML = data.html;
                document.getElementById('results-count').textContent = data.total + ' Jobs Found';
                const rangeText = data.total > 0
                    ? 'Showing ' + data.from + '-' + data.to + ' of ' + data.total
                    : 'No results found';
                document.getElementById('results-range').textContent = rangeText;
                document.getElementById('pagination-container').innerHTML = data.pagination;
                // Re-attach pagination handlers
                attachPaginationHandlers();
            })
            .catch(() => {
                container.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-red-500">Failed to load jobs. Please try again.</p></div>';
            });
    }

    // Get current filter params
    function getFilterParams() {
        const form = document.getElementById('filter-form');
        const searchForm = document.getElementById('search-form');
        const params = new URLSearchParams(new FormData(form));
        // Also include search form values
        const searchParams = new FormData(searchForm);
        for (const [key, val] of searchParams) {
            if (val) params.set(key, val);
        }
        return params.toString();
    }

    // Filter change -> AJAX load
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function() {
                loadJobs(getFilterParams());
            });
        });
    }

    // Search form submit -> AJAX load
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            loadJobs(getFilterParams());
        });
    }

    // Pagination handlers
    function attachPaginationHandlers() {
        document.querySelectorAll('#pagination-container a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                loadJobs(url.searchParams.toString());
            });
        });
    }

    // Clear filters
    document.querySelectorAll('a[href="{{ route('jobs') }}"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadJobs('');
        });
    });

    // Initial load
    loadJobs(getFilterParams());
});
</script>
@endsection

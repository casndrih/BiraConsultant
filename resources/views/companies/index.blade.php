@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
@include('partials.skeleton-styles')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <section class="qj-search-hero qj-search-hero--companies">
        <div class="qj-search-hero__inner">
            <span class="qj-search-hero__eyebrow">Meet the teams behind the work</span>
            <h1 class="qj-search-hero__title">Find your next great team.</h1>
            <p class="qj-search-hero__subtitle">Explore employers and the opportunities they are building.</p>

            <form method="GET" action="{{ route('companies') }}" id="search-form" class="qj-search-panel">
                <div class="qj-search-fields qj-search-fields--companies">
                    <label class="qj-search-field">
                        <span>Company name</span>
                        <span class="qj-search-input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="10.8" cy="10.8" r="6.8" />
                                <path stroke-linecap="round" d="m16 16 4.2 4.2" />
                            </svg>
                            <input type="text" name="search" placeholder="Search companies..." value="{{ request('search') }}" class="qj-search-input">
                        </span>
                    </label>
                    <button type="submit" class="qj-search-submit">
                        Search companies
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h11m-4-4 4 4-4 4" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Companies Grid -->
    <div class="container mx-auto px-4 py-8 qj-results-container">
        @include('partials.adsense', ['placement' => 'companies'])
        <!-- Results Info -->
        <div class="qj-results-heading mb-8" id="results-info">
            <h2 id="results-count">
                Loading...
            </h2>
            <div id="results-range">
                &nbsp;
            </div>
        </div>

        <!-- Companies Grid (skeleton initially) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="companies-container">
            @for ($i = 0; $i < 6; $i++)
                @include('components.skeleton-company-card')
            @endfor
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-8" id="pagination-container">
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
        const container = document.getElementById('companies-container');
        container.innerHTML = '';
        for (let i = 0; i < 6; i++) {
            container.innerHTML += `<div class="bg-white dark:bg-custom-dark rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 rounded-lg skeleton-box flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <div class="h-5 skeleton-box rounded w-3/4 mb-2"></div>
                            <div class="h-3 skeleton-box rounded w-1/2"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="h-3 skeleton-box rounded w-full mb-1.5"></div>
                        <div class="h-3 skeleton-box rounded w-5/6 mb-1.5"></div>
                        <div class="h-3 skeleton-box rounded w-4/6"></div>
                    </div>
                    <div class="space-y-2 mb-4">
                        <div class="h-3 skeleton-box rounded w-2/3"></div>
                        <div class="h-3 skeleton-box rounded w-1/2"></div>
                    </div>
                    <div class="h-9 w-full skeleton-box rounded-full"></div>
                </div>
            </div>`;
        }
        document.getElementById('results-count').textContent = 'Loading...';
        document.getElementById('results-range').innerHTML = '&nbsp;';
        document.getElementById('pagination-container').innerHTML = '';
    }

    // Load companies via AJAX
    function loadCompanies(params) {
        showSkeletons();
        const queryString = params ? '?' + params : '';
        fetch('/companies/list' + queryString, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                document.getElementById('companies-container').innerHTML = data.html;
                window.initQjCompanyPopovers?.();
                document.getElementById('results-count').textContent = data.total + ' Companies Found';
                const rangeText = data.total > 0
                    ? 'Showing ' + data.from + '-' + data.to + ' of ' + data.total
                    : 'No results found';
                document.getElementById('results-range').textContent = rangeText;
                document.getElementById('pagination-container').innerHTML = data.pagination;
                attachPaginationHandlers();
            })
            .catch(() => {
                document.getElementById('companies-container').innerHTML = '<div class="col-span-full text-center py-12"><p class="text-red-500">Failed to load companies. Please try again.</p></div>';
            });
    }

    // Pagination handlers
    function attachPaginationHandlers() {
        document.querySelectorAll('#pagination-container a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                loadCompanies(url.searchParams.toString());
            });
        });
    }

    // Search form submit -> AJAX load
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(this)).toString();
            loadCompanies(params);
        });
    }

    // Search input with debounce
    const searchInput = document.getElementById('search-form')?.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                const params = new URLSearchParams(new FormData(searchForm)).toString();
                loadCompanies(params);
            }, 400);
        });
    }

    // Initial load
    loadCompanies('');
});
</script>
@endsection

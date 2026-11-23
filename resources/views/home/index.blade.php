@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
@include('partials.skeleton-styles')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <section class="bc-search-hero bc-search-hero--home">
        <div class="bc-search-hero__inner">
            <span class="bc-search-hero__eyebrow">Your next chapter starts here</span>
            <h1 class="bc-search-hero__title">Good work. Good people. Your next move.</h1>
            <p class="bc-search-hero__subtitle">{{ $totalJobs ?? 0 }}+ opportunities from {{ $totalCompanies ?? 0 }}+ employers across Papua New Guinea.</p>

            <form method="GET" action="{{ route('search') }}" class="bc-search-panel bc-search-panel--home">
                <div class="bc-search-fields bc-search-fields--home">
                    <label class="bc-search-field">
                        <span>What are you looking for?</span>
                        <span class="bc-search-input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="10.8" cy="10.8" r="6.8" />
                                <path stroke-linecap="round" d="m16 16 4.2 4.2" />
                            </svg>
                            <input type="text" name="q" placeholder="Job title, skill, or company" class="bc-search-input">
                        </span>
                    </label>
                    <button type="submit" class="bc-search-submit">
                        Search jobs
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h11m-4-4 4 4-4 4" />
                        </svg>
                    </button>
                </div>
            </form>
            <div class="bc-search-hero__note">Search by role, skill, or company name</div>
        </div>
    </section>

    <!-- Stats -->
    <div class="container mx-auto px-4 py-8">
        @include('partials.adsense', ['placement' => 'home'])
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-black dark:text-white">{{ $totalJobs ?? 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Active Jobs</div>
            </div>
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-black dark:text-white">{{ $totalCompanies ?? 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Companies</div>
            </div>
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-black dark:text-white">{{ $categories->count() ?? 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Categories</div>
            </div>
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 text-center">
                <div class="text-2xl font-bold text-black dark:text-white">500+</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Job Seekers</div>
            </div>
        </div>

        <!-- Jobs across Papua New Guinea -->
        <section class="bc-jobs-map-card mb-12" aria-labelledby="bc-jobs-map-title">
            <div class="bc-jobs-map__header">
                <div>
                    <span class="bc-jobs-map__eyebrow">Across Papua New Guinea</span>
                    <h2 id="bc-jobs-map-title">Explore jobs by location</h2>
                    <p>Choose a pin to see current openings in that area.</p>
                </div>
                <span class="bc-jobs-map__total">{{ number_format($totalJobs ?? 0) }} active jobs</span>
            </div>

            <div class="bc-jobs-map__layout">
                <figure class="bc-jobs-map__figure">
                    <div class="bc-jobs-map__canvas">
                        <img class="bc-jobs-map__image"
                             src="{{ asset('images/papua-new-guinea-location-map.svg') }}"
                             alt="Map of Papua New Guinea with clickable job locations"
                             loading="lazy" decoding="async">

                        @foreach($jobMapLocations as $pin)
                            <a class="bc-jobs-map__pin"
                               href="{{ route('jobs', ['location' => $pin['query']]) }}"
                               style="--pin-x: {{ $pin['x'] }}%; --pin-y: {{ $pin['y'] }}%; --pin-badge-offset: {{ $pin['badge_offset'] }}px;"
                               aria-label="View {{ number_format($pin['count']) }} {{ trans_choice('job|jobs', $pin['count']) }} in {{ $pin['name'] }}">
                                <span class="bc-jobs-map__pin-count">{{ number_format($pin['count']) }}</span>
                                <svg viewBox="0 0 24 28" aria-hidden="true" focusable="false">
                                    <path d="M12 27s10-9.1 10-16A10 10 0 1 0 2 11c0 6.9 10 16 10 16Z" />
                                    <circle cx="12" cy="10.5" r="3.25" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                    <figcaption class="bc-jobs-map__credit">
                        Adapted from the <a href="https://commons.wikimedia.org/wiki/File:Papua_New_Guinea_location_map.svg" target="_blank" rel="noopener">Papua New Guinea location map</a>
                        by <a href="https://commons.wikimedia.org/wiki/User:NordNordWest" target="_blank" rel="noopener">NordNordWest</a>;
                        <a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank" rel="noopener">CC BY-SA 3.0</a>. Display colors adjust for the selected theme.
                    </figcaption>
                </figure>

                <nav class="bc-jobs-map__locations" aria-label="Browse jobs by location">
                    <h3>Jobs by location</h3>
                    <ul>
                        @foreach($jobMapLocations as $pin)
                            <li>
                                <a href="{{ route('jobs', ['location' => $pin['query']]) }}">
                                    <span>{{ $pin['name'] }}</span>
                                    <strong>{{ number_format($pin['count']) }}</strong>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </section>

        <!-- Categories -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-black dark:text-white mb-6">Browse by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('jobs', ['category' => $category->id]) }}"
                       class="bc-category-card group">
                        <div class="text-base font-medium text-black dark:text-white group-hover:text-black">{{ $category->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $category->jobs_count ?? 0 }} jobs</div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Latest Jobs -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-black dark:text-white">Latest Jobs</h2>
                <a href="{{ route('jobs') }}" class="bc-inline-link">View all jobs <span aria-hidden="true">→</span></a>
            </div>
            <!-- Advanced Filters for Latest Jobs -->
        <div class="bc-home-filters">
            <input type="text" id="filter-search" placeholder="Search jobs..." class="bc-home-filter-control">
            <select id="filter-category" class="bc-home-filter-control">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <select id="filter-jobtype" class="bc-home-filter-control">
                <option value="">All Job Types</option>
                @foreach ($jobTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            <select id="filter-experience" class="bc-home-filter-control">
                <option value="">All Experience Levels</option>
                @foreach ($experienceLevels as $exp)
                    <option value="{{ $exp->id }}">{{ $exp->name }}</option>
                @endforeach
            </select>
        </div>
        <div id="latest-jobs-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @for ($i = 0; $i < 8; $i++)
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-sm p-5">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded skeleton-box"></div>
                    <div class="flex-1">
                        <div class="h-3 skeleton-box rounded w-3/4 mb-2"></div>
                        <div class="h-3 skeleton-box rounded w-1/2"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="h-3 skeleton-box rounded w-full mb-1"></div>
                    <div class="h-3 skeleton-box rounded w-5/6"></div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="h-5 w-16 skeleton-box rounded-full"></div>
                    <div class="h-8 w-20 skeleton-box rounded-full"></div>
                </div>
            </div>
            @endfor
        </div>
        <!-- End Advanced Filters -->
        </div>

        <!-- Featured Jobs -->
        <div class="mb-12">
            <div class="bc-featured-panel">
                <h2 class="bc-featured-panel__title text-2xl font-bold mb-6">Featured Jobs</h2>
                <div id="featured-jobs-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @for ($i = 0; $i < 4; $i++)
                    <div class="bg-white/10 rounded-lg p-5">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="w-10 h-10 rounded bg-white/20"></div>
                            <div class="flex-1">
                                <div class="h-3 bg-white/20 rounded w-3/4 mb-2"></div>
                                <div class="h-3 bg-white/20 rounded w-1/2"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="h-3 bg-white/20 rounded w-full mb-1"></div>
                            <div class="h-3 bg-white/20 rounded w-5/6"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="h-5 w-16 bg-white/20 rounded-full"></div>
                            <div class="h-8 w-20 bg-white/20 rounded-full"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Top Companies -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-black dark:text-white">Top Companies</h2>
                <a href="{{ route('companies') }}" class="bc-inline-link">View all companies <span aria-hidden="true">→</span></a>
            </div>
            <div id="companies-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @for ($i = 0; $i < 6; $i++)
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-4 text-center">
                    <div class="w-12 h-12 rounded-lg mx-auto mb-2 skeleton-box"></div>
                    <div class="h-3 skeleton-box rounded w-3/4 mx-auto mb-1"></div>
                    <div class="h-2 skeleton-box rounded w-1/2 mx-auto"></div>
                </div>
                @endfor
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-black dark:bg-custom-dark rounded-lg p-8 text-center">
            <h2 class="text-2xl font-bold text-white mb-4">Ready to take the next step in your career?</h2>
            <p class="text-gray-300 mb-6">Join thousands of job seekers who found their dream job through BiraConsultant.</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('jobs') }}" class="bc-action-button bc-action-button--primary">
                    Browse Jobs
                </a>
                @guest
                    <a href="#" data-modal-target="signup-modal" data-modal-show="signup-modal" class="bg-white hover:bg-gray-100 text-black font-bold py-3 px-8 rounded-full transition duration-200">
                        Sign Up Free
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function(){
    // Load latest jobs on page load (replaces skeleton)
    $.ajax({
        url: '{{ route('latest.jobs') }}',
        headers: {'X-Requested-With':'XMLHttpRequest'},
        success: function(html){ $('#latest-jobs-container').html(html); },
        error: function(){ console.error('Failed to load latest jobs'); }
    });

    // Load featured jobs on page load (replaces skeleton)
    $.ajax({
        url: '{{ route('featured.jobs') }}',
        headers: {'X-Requested-With':'XMLHttpRequest'},
        success: function(html){ $('#featured-jobs-container').html(html); },
        error: function(){ console.error('Failed to load featured jobs'); }
    });

    // Load companies on page load (replaces skeleton)
    $.ajax({
        url: '{{ route('home.companies') }}',
        headers: {'X-Requested-With':'XMLHttpRequest'},
        success: function(html){ $('#companies-container').html(html); },
        error: function(){ console.error('Failed to load companies'); }
    });

    // Advanced filter change handler (selects)
    $('#filter-category, #filter-jobtype, #filter-experience').on('change', function(){
        fetchFilteredJobs();
    });

    // Search input with debounce
    let searchTimer;
    $('#filter-search').on('input', function(){
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchFilteredJobs, 400);
    });

    function fetchFilteredJobs(){
        const params = new URLSearchParams();
        const cat = $('#filter-category').val();
        const type = $('#filter-jobtype').val();
        const exp = $('#filter-experience').val();
        const search = $('#filter-search').val();
        if(cat) params.append('category', cat);
        if(type) params.append('type', type);
        if(exp) params.append('experience', exp);
        if(search) params.append('search', search);
        // Show loading
            $('#latest-jobs-container').html('<div class="col-span-full text-center py-12"><div class="bc-spinner"></div><p class="text-gray-500 mt-2">Loading jobs...</p></div>');
        $.ajax({
            url: '{{ route('latest.jobs') }}?' + params.toString(),
            headers: {'X-Requested-With':'XMLHttpRequest'},
            success: function(html){ $('#latest-jobs-container').html(html); },
            error: function(){ $('#latest-jobs-container').html('<div class="col-span-full text-center py-12"><p class="text-red-500">Failed to load jobs. Please try again.</p></div>'); }
        });
    }
});
</script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

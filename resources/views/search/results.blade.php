@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <section class="bc-search-hero bc-search-hero--results">
        <div class="bc-search-hero__inner">
            <span class="bc-search-hero__eyebrow">Search BiraConsultant</span>
            <h1 class="bc-search-hero__title">Results for “{{ $query }}”</h1>
            <form method="GET" action="{{ route('search') }}" class="bc-search-panel bc-search-panel--home">
                <div class="bc-search-fields bc-search-fields--home">
                    <label class="bc-search-field">
                        <span>Refine your search</span>
                        <span class="bc-search-input-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="10.8" cy="10.8" r="6.8" />
                                <path stroke-linecap="round" d="m16 16 4.2 4.2" />
                            </svg>
                            <input type="text" name="q" value="{{ $query }}" placeholder="Job title, skill, or company" class="bc-search-input">
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
        </div>
    </section>

    <div class="container mx-auto px-4 py-8 bc-results-container">

        @if($jobs->count() > 0)
            <div class="bc-results-heading mb-6">
                <h2>{{ $jobs->total() }} {{ $jobs->total() === 1 ? 'job' : 'jobs' }} found</h2>
                <p>Opportunities matching your search</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($jobs as $job)
                    @include('components.job-card', ['job' => $job])
                @endforeach
            </div>
            <div class="flex justify-center">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-black dark:text-white mb-2">No jobs found</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your search terms or browse all jobs.</p>
                <a href="{{ route('jobs') }}" class="bc-action-button bc-action-button--primary">
                    Browse All Jobs
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

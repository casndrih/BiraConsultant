@php
    $showSalary = $showSalary ?? true;
    $showApplyButton = $showApplyButton ?? true;
    $hasApplied = $hasApplied ?? false;
    $company = $job->company;
    $companyName = $company?->name ?? 'Confidential employer';
    $postedLabel = $job->created_at
        ? \Carbon\Carbon::parse($job->created_at)->diffForHumans()
        : 'Recently posted';
@endphp

<article class="qj-job-card">
    <div class="qj-job-card__top">
        <div class="qj-company-logo" aria-hidden="true">
            @if($company?->logo)
                <img src="{{ asset('storage/' . $company->logo) }}"
                     alt=""
                     loading="lazy">
            @else
                <span>{{ mb_strtoupper(mb_substr($companyName, 0, 1)) }}</span>
            @endif
        </div>

        <div class="qj-job-card__heading">
            <p class="qj-job-card__company">@if($company) @include('components.company-popover', ['company' => $company]) @else {{ $companyName }} @endif</p>
            <h3 class="qj-job-card__title">
                <a href="{{ route('job.detail', $job->slug) }}">{{ $job->title }}</a>
            </h3>
        </div>
    </div>

    <div class="qj-job-card__badges">
        <span class="qj-job-badge">{{ $job->jobType->name ?? 'Full time' }}</span>
        <span class="qj-job-badge qj-job-badge--muted">{{ $job->experienceLevel->name ?? 'Entry level' }}</span>
    </div>

    @if($showSalary && $job->salary)
        <p class="qj-job-card__salary">{{ $job->salary }}</p>
    @endif

    <p class="qj-job-card__description">{{ Str::limit(strip_tags($job->description ?? ''), 150) }}</p>

    <div class="qj-job-card__location">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.2c0 5.2-7.5 11-7.5 11s-7.5-5.8-7.5-11a7.5 7.5 0 1 1 15 0Z" />
            <circle cx="12" cy="10" r="2.4" />
        </svg>
        <span>{{ $job->location ?: 'Remote' }}</span>
    </div>

    <div class="qj-job-card__footer">
        <div class="qj-job-card__labels">
            @if($job->category)
                <span class="qj-category-tag">{{ $job->category->name }}</span>
            @endif
            @if($job->deadline)
                <span class="qj-job-card__posted">Closes {{ \Carbon\Carbon::parse($job->deadline)->format('M j') }}</span>
            @else
                <span class="qj-job-card__posted">{{ $postedLabel }}</span>
            @endif
        </div>

        @if($showApplyButton)
            @if($hasApplied)
                <button class="qj-action-button qj-action-button--applied" type="button" disabled>
                    Applied
                </button>
            @else
                <a href="{{ route('job.detail', $job->slug) }}" class="qj-action-button qj-action-button--primary">
                    View job
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h11m-4-4 4 4-4 4" />
                    </svg>
                </a>
            @endif
        @endif
    </div>
</article>

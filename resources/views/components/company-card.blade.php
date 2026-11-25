@php
    $companyLocation = collect([$company->city, optional($company->country)->name])
        ->filter(fn ($value) => filled($value))
        ->implode(', ');
    $openJobs = $company->jobs_count ?? 0;
@endphp

<article class="qj-company-card">
    <div class="qj-company-card__top">
        <div class="qj-company-logo qj-company-logo--large qj-company-logo--bare" aria-hidden="true">
            @if($company->logo_url)
                <img src="{{ $company->logo_url }}" alt="" loading="lazy">
            @elseif($company->slug === 'bank-south-pacific')
                <img src="{{ asset('images/companies/bsp-logo-dark.svg') }}" alt="" loading="lazy">
            @elseif($company->slug === 'kumul-petroleum-holdings')
                <img src="https://kumulpetroleum.com/wp-content/uploads/2023/09/KPHL_Main_logo.png" alt="" loading="lazy">
            @elseif($company->slug === 'lae-city-pharmacy')
                <img src="https://cpl.com.pg/wp-content/uploads/2026/07/City-Pharmacy-Brand-Logo-Pantones.pdf-image-002-768x202.png" alt="" loading="lazy">
            @elseif($company->slug === 'highlands-engineering-services')
                <img src="{{ asset('images/companies/highlands-engineering-services.svg') }}" alt="" loading="lazy">
            @else
                <span>{{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}</span>
            @endif
        </div>
        <div class="qj-company-card__identity">
            <span class="qj-company-card__eyebrow">Hiring on BiraConsultant</span>
            <h3>@include('components.company-popover', ['company' => $company])</h3>
            @if($companyLocation)
                <p class="qj-company-card__location">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.2c0 5.2-7.5 11-7.5 11s-7.5-5.8-7.5-11a7.5 7.5 0 1 1 15 0Z" />
                        <circle cx="12" cy="10" r="2.4" />
                    </svg>
                    {{ $companyLocation }}
                </p>
            @endif
        </div>
        <span class="qj-company-card__count"><strong>{{ $openJobs }}</strong><span>open roles</span></span>
    </div>

    <p class="qj-company-card__description">
        {{ Str::limit(strip_tags($company->description ?? 'Learn more about this employer and explore their latest opportunities.'), 145) }}
    </p>

    @if($company->website)
        <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="qj-company-card__website">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 12h17M12 3c2.3 2.5 3.5 5.5 3.5 9s-1.2 6.5-3.5 9c-2.3-2.5-3.5-5.5-3.5-9S9.7 5.5 12 3Z" />
            </svg>
            Visit company website
        </a>
    @endif

    <a href="{{ route('company.detail', $company->slug) }}" class="qj-action-button qj-action-button--primary qj-company-card__button">
        Explore open roles
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h11m-4-4 4 4-4 4" />
        </svg>
    </a>
</article>

@forelse($companies as $company)
    <a href="{{ route('company.detail', $company->slug) }}" class="qj-company-tile">
        <div class="qj-company-logo qj-company-logo--tile" aria-hidden="true">
            @if($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="" loading="lazy">
            @else
                <span>{{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}</span>
            @endif
        </div>
        <div class="qj-company-tile__copy">
            <h3>{{ $company->name }}</h3>
            <p>{{ $company->jobs_count ?? 0 }} open {{ ($company->jobs_count ?? 0) === 1 ? 'role' : 'roles' }}</p>
        </div>
        <svg class="qj-company-tile__arrow" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h11m-4-4 4 4-4 4" />
        </svg>
    </a>
@empty
    <div class="col-span-full text-center py-8">
        <p class="text-gray-500 dark:text-gray-400">No companies registered yet.</p>
    </div>
@endforelse

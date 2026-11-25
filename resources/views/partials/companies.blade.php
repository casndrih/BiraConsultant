@forelse($companies as $company)
    <article class="qj-company-tile">
        <div class="qj-company-tile__main-link flex items-center gap-4 min-w-0">
            <div class="qj-company-logo qj-company-logo--tile qj-company-logo--bare" aria-hidden="true">
                @if($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="" loading="lazy">
                @elseif($company->slug === 'bank-south-pacific')
                    <img src="{{ asset('images/companies/bsp-logo-dark.svg') }}" alt="" class="w-full h-full object-contain" loading="lazy">
                @elseif($company->slug === 'kumul-petroleum-holdings')
                    <img src="https://kumulpetroleum.com/wp-content/uploads/2023/09/KPHL_Main_logo.png" alt="" class="w-full h-full object-contain" loading="lazy">
                @elseif($company->slug === 'lae-city-pharmacy')
                    <img src="https://cpl.com.pg/wp-content/uploads/2026/07/City-Pharmacy-Brand-Logo-Pantones.pdf-image-002-768x202.png" alt="" class="w-full h-full object-contain" loading="lazy">
                @elseif($company->slug === 'highlands-engineering-services')
                    <img src="{{ asset('images/companies/highlands-engineering-services.svg') }}" alt="" class="w-full h-full object-contain" loading="lazy">
                @else
                    <span>{{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="qj-company-tile__copy min-w-0">
                <h3>@include('components.company-popover', ['company' => $company])</h3>
                <p>{{ $company->jobs_count ?? 0 }} open {{ ($company->jobs_count ?? 0) === 1 ? 'role' : 'roles' }}</p>
            </div>
        </div>
        <span class="qj-company-tile__arrow" aria-hidden="true">→</span>
    </article>
@empty
    <div class="col-span-full text-center py-8">
        <p class="text-gray-500 dark:text-gray-400">No companies registered yet.</p>
    </div>
@endforelse

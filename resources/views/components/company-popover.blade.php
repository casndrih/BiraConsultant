@php
    $popoverId = 'qj-company-popover-' . $company->id . '-' . \Illuminate\Support\Str::uuid();
    $popoverLocation = collect([$company->city, $company->location, optional($company->country)->name])
        ->filter(fn ($value) => filled($value))
        ->unique()
        ->implode(', ');
    $popoverIndustry = $company->industry ?? null;
    $popoverLogo = $company->logo_url ?? null;
    $popoverDescription = trim(strip_tags((string) ($company->description ?? '')));
@endphp
<span class="qj-company-popover-wrap">
    <a href="{{ route('company.detail', $company->slug) }}"
       class="qj-company-popover-trigger"
       data-popover-target="{{ $popoverId }}"
       data-popover-trigger="hover"
       data-popover-placement="bottom"
       aria-describedby="{{ $popoverId }}">
        {{ $company->name }}
    </a>
    <span id="{{ $popoverId }}" role="tooltip" class="qj-company-popover invisible absolute z-50 inline-block opacity-0 transition-opacity duration-200">
        <span class="qj-company-popover__head">
            <span class="qj-company-popover__logo">
                @if($popoverLogo)
                    <img src="{{ $popoverLogo }}" alt="" loading="lazy">
                @else
                    {{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}
                @endif
            </span>
            <span class="min-w-0">
                <span class="qj-company-popover__name block">{{ $company->name }}</span>
                @if($popoverIndustry)
                    <span class="qj-company-popover__meta block">{{ $popoverIndustry }}</span>
                @endif
                @if($popoverLocation)
                    <span class="qj-company-popover__meta block">{{ $popoverLocation }}</span>
                @endif
            </span>
        </span>
        @if($popoverDescription)
            <span class="qj-company-popover__description block">{{ \Illuminate\Support\Str::limit($popoverDescription, 150) }}</span>
        @endif
        <span class="qj-company-popover__footer">
            <span>{{ $company->jobs_count ?? $company->jobs()->where('status', 1)->count() }} open roles</span>
            <a href="{{ route('company.detail', $company->slug) }}" class="qj-company-popover__link">View company <span aria-hidden="true">→</span></a>
        </span>
    </span>
</span>

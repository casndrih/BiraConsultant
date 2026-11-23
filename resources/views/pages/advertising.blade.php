@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="bc-content-page">
    <section class="bc-content-hero">
        <div class="bc-content-hero__inner">
            <span class="bc-content-hero__eyebrow">Advertise with BiraConsultant</span>
            <h1 class="bc-content-hero__title">Meet people ready for their next move.</h1>
            <p class="bc-content-hero__subtitle">Reach job seekers and employers across Papua New Guinea with sponsored placements on BiraConsultant.</p>
        </div>
    </section>

    <div class="bc-content-wrap">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <article class="bc-content-panel">
                <span class="bc-advertising-eyebrow">01 · Visibility</span>
                <h2 class="bc-content-title">Display placements</h2>
                <p class="bc-content-copy">Place your campaign on the home, jobs, companies, or careers content pages.</p>
            </article>
            <article class="bc-content-panel">
                <span class="bc-advertising-eyebrow">02 · Hiring</span>
                <h2 class="bc-content-title">Promoted opportunities</h2>
                <p class="bc-content-copy">Bring a role or employer profile to the attention of more relevant job seekers.</p>
            </article>
            <article class="bc-content-panel">
                <span class="bc-advertising-eyebrow">03 · Local reach</span>
                <h2 class="bc-content-title">PNG audiences</h2>
                <p class="bc-content-copy">Share your organization, services, and opportunities with people across the country.</p>
            </article>
        </div>

        <section class="bc-content-panel">
            <h2 class="bc-content-title">Plan a campaign</h2>
            <p class="bc-content-copy mb-5">Contact the BiraConsultant team with your campaign goals, audience, and preferred dates. We’ll share the available placements and pricing.</p>
            <a href="{{ route('contact', ['subject' => 'Advertising']) }}" class="bc-action-button bc-action-button--primary">Ask about advertising</a>
        </section>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

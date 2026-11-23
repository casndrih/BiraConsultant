@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <section class="qj-content-hero">
        <div class="qj-content-hero__inner">
            <span class="qj-content-hero__eyebrow">Advertise with QJobs</span>
            <h1 class="qj-content-hero__title">Meet people ready for their next move.</h1>
            <p class="qj-content-hero__subtitle">Reach job seekers and employers across Papua New Guinea with sponsored placements on QJobs.</p>
        </div>
    </section>

    <div class="qj-content-wrap">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <article class="qj-content-panel">
                <span class="qj-advertising-eyebrow">01 · Visibility</span>
                <h2 class="qj-content-title">Display placements</h2>
                <p class="qj-content-copy">Place your campaign on the home, jobs, companies, or careers content pages.</p>
            </article>
            <article class="qj-content-panel">
                <span class="qj-advertising-eyebrow">02 · Hiring</span>
                <h2 class="qj-content-title">Promoted opportunities</h2>
                <p class="qj-content-copy">Bring a role or employer profile to the attention of more relevant job seekers.</p>
            </article>
            <article class="qj-content-panel">
                <span class="qj-advertising-eyebrow">03 · Local reach</span>
                <h2 class="qj-content-title">PNG audiences</h2>
                <p class="qj-content-copy">Share your organization, services, and opportunities with people across the country.</p>
            </article>
        </div>

        <section class="qj-content-panel">
            <h2 class="qj-content-title">Plan a campaign</h2>
            <p class="qj-content-copy mb-5">Contact the QJobs team with your campaign goals, audience, and preferred dates. We’ll share the available placements and pricing.</p>
            <a href="{{ route('contact', ['subject' => 'Advertising']) }}" class="qj-action-button qj-action-button--primary">Ask about advertising</a>
        </section>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

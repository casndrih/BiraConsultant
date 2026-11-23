@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="bc-content-page">
    <section class="bc-content-hero">
        <div class="bc-content-hero__inner">
            <span class="bc-content-hero__eyebrow">Built for the Pacific</span>
            <h1 class="bc-content-hero__title">About BiraConsultant</h1>
            <p class="bc-content-hero__subtitle">A simpler way to connect people with meaningful work and employers with great talent.</p>
        </div>
    </section>
    <div class="bc-content-wrap bc-content-wrap--narrow">
        <div class="bc-content-panel bc-content-copy">
        <p class="text-lg mb-5">
            BiraConsultant is a modern job-board platform that connects talented professionals with leading companies across the Pacific region. Our mission is to simplify recruitment, empower job-seekers, and provide companies with reliable hiring tools.
        </p>
        <p class="text-lg">
            Founded in 2025, we focus on transparency, fast matching, and a seamless user experience. Our platform supports resume uploads, identity verification, and a multi-stage application workflow.
        </p>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

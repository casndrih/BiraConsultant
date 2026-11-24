@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <section class="qj-content-hero">
        <div class="qj-content-hero__inner">
            <span class="qj-content-hero__eyebrow">Built for the Pacific</span>
            <h1 class="qj-content-hero__title">About BiraConsultant</h1>
            <p class="qj-content-hero__subtitle">A simpler way to connect people with meaningful work and employers with great talent.</p>
        </div>
    </section>
    <div class="qj-content-wrap qj-content-wrap--narrow">
        <div class="qj-content-panel qj-content-copy">
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

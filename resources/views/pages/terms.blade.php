@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="bc-content-page">
    <section class="bc-content-hero">
        <div class="bc-content-hero__inner">
            <span class="bc-content-hero__eyebrow">Using BiraConsultant</span>
            <h1 class="bc-content-hero__title">Terms of Service</h1>
            <p class="bc-content-hero__subtitle">The terms that apply when you use the BiraConsultant platform.</p>
        </div>
    </section>
    <div class="bc-content-wrap bc-content-wrap--narrow">
        <div class="bc-content-panel bc-article-prose">
            {!! $terms ?? 'Loading terms of service...' !!}
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

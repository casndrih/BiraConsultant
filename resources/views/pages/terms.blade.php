@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <section class="qj-content-hero">
        <div class="qj-content-hero__inner">
            <span class="qj-content-hero__eyebrow">Using BiraConsultant</span>
            <h1 class="qj-content-hero__title">Terms of Service</h1>
            <p class="qj-content-hero__subtitle">The terms that apply when you use the BiraConsultant platform.</p>
        </div>
    </section>
    <div class="qj-content-wrap qj-content-wrap--narrow">
        <div class="qj-content-panel qj-article-prose">
            {!! $terms ?? 'Loading terms of service...' !!}
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

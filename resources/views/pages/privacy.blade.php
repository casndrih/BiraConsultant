@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="bc-content-page">
    <section class="bc-content-hero">
        <div class="bc-content-hero__inner">
            <span class="bc-content-hero__eyebrow">Your data matters</span>
            <h1 class="bc-content-hero__title">Privacy Policy</h1>
            <p class="bc-content-hero__subtitle">How BiraConsultant handles information used to support your job search and hiring activity.</p>
        </div>
    </section>
    <div class="bc-content-wrap bc-content-wrap--narrow">
        <div class="bc-content-panel bc-article-prose">
            {!! $policy ?? 'Loading privacy policy...' !!}
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

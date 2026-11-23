@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <section class="qj-content-hero">
        <div class="qj-content-hero__inner">
            <span class="qj-content-hero__eyebrow">Your data matters</span>
            <h1 class="qj-content-hero__title">Privacy Policy</h1>
            <p class="qj-content-hero__subtitle">How QJobs handles information used to support your job search and hiring activity.</p>
        </div>
    </section>
    <div class="qj-content-wrap qj-content-wrap--narrow">
        <div class="qj-content-panel qj-article-prose">
            {!! $policy ?? 'Loading privacy policy...' !!}
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

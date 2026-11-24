@extends('app')

@section('title', 'Password Recovery')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <section class="qj-content-hero">
        <div class="qj-content-hero__inner">
            <span class="qj-content-hero__eyebrow">Account assistance</span>
            <h1 class="qj-content-hero__title">Recover your account</h1>
            <p class="qj-content-hero__subtitle">Enter your account email and we’ll send a password reset link if the address is registered.</p>
        </div>
    </section>
    <div class="qj-content-wrap qj-content-wrap--narrow">
        <div class="max-w-lg mx-auto qj-content-panel">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm mb-5">{{ session('error') }}</div>
            @endif
            @if(session('status'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg text-sm mb-5">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm mb-5"><ul class="list-disc list-inside">@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>
            @endif
            @if($emailLoginEnabled)
                <form method="POST" action="{{ route('forgot-password') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Email address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="email" required class="qj-content-form-control" placeholder="your@email.com">
                    </div>
                    @include('components.captcha')
                    <button type="submit" class="qj-content-button w-full">Send recovery link</button>
                </form>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-300">Password recovery is unavailable while email sign-in is disabled.</p>
            @endif
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-5"><a href="{{ route('login') }}" class="qj-auth-link">Back to sign in</a></p>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

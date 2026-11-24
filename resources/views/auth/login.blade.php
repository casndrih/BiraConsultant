@extends('app')

@section('title', 'Sign In')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <section class="qj-content-hero">
        <div class="qj-content-hero__inner">
            <span class="qj-content-hero__eyebrow">Welcome back</span>
            <h1 class="qj-content-hero__title">Sign in to BiraConsultant</h1>
            <p class="qj-content-hero__subtitle">Access your dashboard, applications and account settings.</p>
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
            @if($googleLoginEnabled)
                <a href="{{ route('auth.google.redirect', ['platform' => 'web']) }}" class="mb-5 w-full flex items-center justify-center gap-3 rounded-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-custom-dark px-5 py-3 text-sm font-medium text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800">
                    <span class="text-lg font-bold text-green-600">G</span> Continue with Google
                </a>
            @endif
            @if($googleLoginEnabled && $emailLoginEnabled)
                <div class="mb-5 flex items-center gap-3 text-xs tracking-wider text-gray-400"><span class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></span>OR CONTINUE WITH EMAIL<span class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></span></div>
            @endif
            @if($emailLoginEnabled)
                <form class="space-y-5" action="{{ route('signIn') }}" method="POST">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="email" required class="qj-content-form-control" placeholder="your@email.com">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-800 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" autocomplete="current-password" required class="qj-content-form-control" placeholder="Your password">
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"><input type="checkbox" name="remember" value="1" class="rounded"> Remember me</label>
                        <a href="{{ route('password.request') }}" class="qj-auth-link text-sm">Forgot password?</a>
                    </div>
                    @include('components.captcha')
                    <button type="submit" class="qj-content-button w-full">Sign in</button>
                </form>
            @elseif(!$googleLoginEnabled)
                <p class="text-sm text-gray-600 dark:text-gray-300">Sign-in is not currently available. Contact the site administrator.</p>
            @endif
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-5">New to BiraConsultant? <a href="{{ route('register') }}" class="qj-auth-link">Create an account</a>.</p>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

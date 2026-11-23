@extends('layouts.app')

@section('content')
<div class="bc-content-panel bc-auth-panel">
    <span class="bc-content-hero__eyebrow bc-auth-eyebrow">Welcome back</span>
    <h2 class="bc-content-title text-center">Sign in to BiraConsultant</h2>
    @if(session('error'))
        <div class="bg-red-100 border-red-400 text-red-700 px-4 py-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif
    <form method="POST" action="{{ route('signIn') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 mb-1" for="email">Email</label>
            <input class="bc-content-form-control" type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 mb-1" for="password">Password</label>
            <input class="bc-content-form-control" type="password" name="password" id="password" required>
        </div>
        <div class="flex items-center mb-4">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label class="text-gray-600 dark:text-gray-400" for="remember">Remember Me</label>
        </div>
        <button type="submit" class="bc-content-button">
            Sign In
        </button>
    </form>
</div>
@endsection

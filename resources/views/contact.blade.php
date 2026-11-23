@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-content-page">
    <section class="qj-content-hero">
        <div class="qj-content-hero__inner">
            <span class="qj-content-hero__eyebrow">We’re here to help</span>
            <h1 class="qj-content-hero__title">Contact QJobs</h1>
            <p class="qj-content-hero__subtitle">Have a question or feedback? Send us a message and our team will get back to you.</p>
        </div>
    </section>
    <div class="qj-content-wrap qj-content-wrap--narrow">
        <div>

            @if(session('success'))
                <div class="qj-status-message mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="qj-content-panel">
                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="qj-content-form-control">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="qj-content-form-control">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject', request('subject')) }}" required
                               class="qj-content-form-control">
                        @error('subject')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message</label>
                        <textarea name="message" rows="6" required
                                  class="qj-content-form-control qj-content-form-control--textarea">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @include('components.captcha')

                    <button type="submit" class="qj-content-button">
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="qj-content-panel qj-content-panel--compact qj-contact-info">
                    <svg class="qj-contact-info__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="font-medium text-black dark:text-white mb-1">Email</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">info@qjobs.com</p>
                </div>
                <div class="qj-content-panel qj-content-panel--compact qj-contact-info">
                    <svg class="qj-contact-info__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <h3 class="font-medium text-black dark:text-white mb-1">Phone</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">+1 (555) 123-4567</p>
                </div>
                <div class="qj-content-panel qj-content-panel--compact qj-contact-info">
                    <svg class="qj-contact-info__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="font-medium text-black dark:text-white mb-1">Location</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Port Moresby, PNG</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

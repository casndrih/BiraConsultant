@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="bc-content-page">
    <section class="bc-content-hero">
        <div class="bc-content-hero__inner">
            <span class="bc-content-hero__eyebrow">For employers</span>
            <h1 class="bc-content-hero__title">Register your company</h1>
            <p class="bc-content-hero__subtitle">Create your BiraConsultant profile and start reaching qualified candidates across the Pacific.</p>
        </div>
    </section>
    <div class="bc-content-wrap bc-content-wrap--narrow">
        <div class="max-w-2xl mx-auto">
            @if(session('success'))
                <div class="bc-status-message mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bc-content-panel">
                <form method="POST" action="{{ route('company.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="bc-content-form-control">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                        <textarea name="description" rows="5" required
                                  class="bc-content-form-control bc-content-form-control--textarea">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="bc-content-form-control">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="bc-content-form-control">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Website</label>
                            <input type="url" name="website" value="{{ old('website') }}"
                                   class="bc-content-form-control">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Industry</label>
                            <input type="text" name="industry" value="{{ old('industry') }}"
                                   class="bc-content-form-control">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">City</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                   class="bc-content-form-control">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Country</label>
                            <select name="country_id"
                                    class="bc-content-form-control">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="bc-content-form-control">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Logo</label>
                        <input type="file" name="logo" accept="image/*"
                               class="bc-content-form-control bc-content-file">
                        <p class="text-xs text-gray-500 mt-1">Max 2MB. Recommended: 200x200px</p>
                    </div>

                    <button type="submit" class="bc-content-button">
                        Register Company
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

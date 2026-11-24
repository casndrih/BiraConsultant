@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <!-- Header -->
    <div class="qj-dashboard-hero">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold mb-1">Company Settings</h1>
            <p class="text-sm opacity-75">{{ $company->name }} — Update your profile and location</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-3xl">

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-700 rounded-lg p-4 mb-6">
            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
        </div>
        @endif

        <form action="{{ route('company.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Basic Info -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                <h3 class="text-base font-semibold text-black dark:text-white mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $company->name) }}" required
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $company->email) }}" required
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Website</label>
                        <input type="url" name="website" value="{{ old('website', $company->website) }}"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Industry</label>
                        <input type="text" name="industry" value="{{ old('industry', $company->industry) }}"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Size</label>
                        <input type="text" name="size" value="{{ old('size', $company->size) }}" placeholder="e.g. 11-50 employees"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Founded Year</label>
                        <input type="number" name="founded_year" value="{{ old('founded_year', $company->founded_year) }}" min="1800" max="{{ date('Y') }}"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">{{ old('description', $company->description) }}</textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo</label>
                        @if($company->logo)
                            <div class="mb-2">
                                <img src="{{ $company->logo_url }}" alt="Logo" class="w-16 h-16 rounded-lg object-cover">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*"
                            class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                <h3 class="text-base font-semibold text-black dark:text-white mb-4">Location</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                        <input type="text" name="address" value="{{ old('address', $company->address) }}"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">City</label>
                        <input type="text" name="city" value="{{ old('city', $company->city) }}"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Country</label>
                        <select name="country_id"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $company->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Map Coordinates -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                <h3 class="text-base font-semibold text-black dark:text-white mb-2">Map Location</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Set your company's coordinates to display on the map. You can <a href="https://www.google.com/maps" target="_blank" class="text-yellow-500 hover:underline">find coordinates on Google Maps</a> by right-clicking your location.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Latitude</label>
                        <input type="text" name="latitude" value="{{ old('latitude', $company->latitude) }}" placeholder="e.g. 9.0192"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Longitude</label>
                        <input type="text" name="longitude" value="{{ old('longitude', $company->longitude) }}" placeholder="e.g. 38.7525"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-custom-dark text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                </div>
                @if($company->latitude && $company->longitude)
                <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <iframe
                        width="100%"
                        height="250"
                        style="border:0"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAPS_API_KEY&q={{ $company->latitude }},{{ $company->longitude }}&zoom=14">
                    </iframe>
                </div>
                @else
                <div class="rounded-lg bg-gray-100 dark:bg-gray-800 h-48 flex items-center justify-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Set latitude and longitude to preview map</p>
                </div>
                @endif
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-8 py-3 bg-yellow-300 hover:bg-yellow-400 text-black font-semibold rounded-full shadow-md transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

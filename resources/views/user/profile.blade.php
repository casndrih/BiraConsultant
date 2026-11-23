@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-dashboard-page">
    <!-- Profile Header -->
    @php($profileCoverUrl = $user->cover ? (str_starts_with(strtolower($user->cover), 'http') ? $user->cover : asset('storage/' . $user->cover)) : '')
    @php($profileImageUrl = $user->image ? (str_starts_with(strtolower($user->image), 'http') ? $user->image : asset('storage/' . $user->image)) : '')
    <div class="qj-dashboard-hero"
         @if($profileCoverUrl) style="background-image: linear-gradient(90deg, rgba(0,0,0,.70), rgba(30,55,20,.52)), url('{{ $profileCoverUrl }}'); background-size: cover; background-position: center;" @endif>
        <div class="qj-dashboard-hero__inner container mx-auto px-4">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative group">
                    @if($profileImageUrl)
                        <img src="{{ $profileImageUrl }}"
                             alt="{{ $user->name }}"
                             class="w-24 h-24 rounded-full object-cover border-4 border-white"
                             id="header-avatar">
                    @else
                        <div class="w-24 h-24 rounded-full border-4 border-white bg-gray-300 flex items-center justify-center" id="header-avatar-placeholder">
                            <span class="text-3xl font-bold text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <label for="profile-image-input" class="absolute bottom-0 right-0 bg-yellow-300 hover:bg-yellow-400 text-black rounded-full p-2 cursor-pointer shadow-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </label>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-3xl font-bold mb-2">{{ $user->name }}</h1>
                    <p class="text-xl opacity-90">{{ $user->email }}</p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 mt-2 text-sm">
                        @if($user->country)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $user->country->name }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Joined {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
                <!-- Profile Completion Badge -->
                <div class="text-center">
                    <div class="relative w-20 h-20">
                        <svg class="w-20 h-20 transform -rotate-90">
                            <circle cx="40" cy="40" r="35" stroke="rgba(255,255,255,0.2)" stroke-width="6" fill="none"/>
                            <circle cx="40" cy="40" r="35" stroke="white" stroke-width="6" fill="none"
                                    stroke-dasharray="{{ 2 * 3.14159 * 35 }}"
                                    stroke-dashoffset="{{ 2 * 3.14159 * 35 * (1 - $user->profile_completion / 100) }}"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-lg font-bold">{{ $user->profile_completion }}%</span>
                        </div>
                    </div>
                    <p class="text-xs opacity-75 mt-1">Profile</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900/30 border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md mb-6">
            <div class="flex flex-wrap border-b border-gray-200 dark:border-gray-700" id="profile-tabs">
                <button onclick="switchTab('personal')" class="tab-btn px-6 py-3 text-sm font-medium transition border-b-2 border-yellow-500 text-yellow-500" data-tab="personal">
                    Personal Info
                </button>
                <button onclick="switchTab('experience')" class="tab-btn px-6 py-3 text-sm font-medium transition border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-yellow-500" data-tab="experience">
                    Work Experience
                </button>
                <button onclick="switchTab('skills')" class="tab-btn px-6 py-3 text-sm font-medium transition border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-yellow-500" data-tab="skills">
                    Skills
                </button>
                <button onclick="switchTab('password')" class="tab-btn px-6 py-3 text-sm font-medium transition border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-yellow-500" data-tab="password">
                    Change Password
                </button>
                <button onclick="switchTab('verification')" class="tab-btn px-6 py-3 text-sm font-medium transition border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-yellow-500" data-tab="verification">
                    Identity Verification
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Personal Information Tab -->
                <div id="tab-personal" class="tab-content">
                    <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-black dark:text-white mb-6">Personal Information</h2>

                        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" id="profile-form">
                            @csrf
                            <!-- Hidden file input for header camera button -->
                            <input type="file" name="image" id="profile-image-input" accept="image/*" class="hidden" onchange="previewImage(this)">
                            <input type="file" name="cover" id="profile-cover-input" accept="image/*" class="hidden" onchange="previewCover(this)">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                           class="w-full px-3 py-2  rounded-md focus:ring-2 focus:ring-yellow-300 focus:border-transparent dark:text-white dark:bg-black">
                                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                           class="w-full px-3 py-2  rounded-md focus:ring-2 focus:ring-yellow-300 focus:border-transparent dark:text-white dark:bg-black">
                                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                           class="w-full px-3 py-2  rounded-md focus:ring-2 focus:ring-yellow-300 focus:border-transparent dark:text-white dark:bg-black">
                                    @if($user->phone)
                                        @if($user->phone_verified)
                                            <p class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Phone verified
                                            </p>
                                        @else
                                            <div class="mt-2" id="phone-verify-section">
                                                <button type="button" onclick="sendPhoneCode()" class="text-xs px-3 py-1.5 bg-yellow-300 hover:bg-yellow-400 text-black font-medium rounded-md transition">
                                                    Verify Phone Number
                                                </button>
                                            </div>
                                            <div id="phone-code-section" class="hidden mt-2">
                                                <div class="flex gap-2">
                                                    <input type="text" id="phone-code-input" placeholder="Enter 6-digit code" maxlength="6"
                                                           class="flex-1 px-3 py-1.5 rounded-md text-sm focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                                                    <button type="button" onclick="verifyPhoneCode()" class="text-xs px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white font-medium rounded-md transition">
                                                        Verify
                                                    </button>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1" id="phone-code-msg"></p>
                                            </div>
                                        @endif>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                                    <input type="date" name="birthday" value="{{ old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '') }}"
                                           class="w-full px-3 py-2  rounded-md focus:ring-2 focus:ring-yellow-300 focus:border-transparent dark:text-white dark:bg-black">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gender</label>
                                    <select name="gender"
                                            class="w-full px-3 py-2 rounded-md focus:ring-2 focus:ring-yellow-300 focus: dark:text-white dark:bg-black">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ $user->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $user->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ $user->gender === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Country</label>
                                    <select name="country_id"
                                            class="w-full px-3 py-2 rounded-md focus:ring-2 focus:ring-yellow-300 focus: dark:text-white dark:bg-black">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ $user->country_id == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Province (PNG)</label>
                                    @php($currentProvince = old('state', $user->state))
                                    <select name="state"
                                            class="w-full px-3 py-2 rounded-md focus:ring-2 focus:ring-yellow-300 focus:border-transparent dark:text-white dark:bg-black">
                                        <option value="">Select a province</option>
                                        @if($currentProvince && !in_array($currentProvince, config('png.provinces', []), true))
                                            <option value="{{ $currentProvince }}" selected>{{ $currentProvince }} (current)</option>
                                        @endif
                                        @foreach(config('png.provinces', []) as $province)
                                            <option value="{{ $province }}" {{ $currentProvince === $province ? 'selected' : '' }}>{{ $province }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Website</label>
                                    <input type="url" name="website" value="{{ old('website', $user->website) }}"
                                           placeholder="https://your-website.com"
                                           class="w-full px-3 py-2  rounded-md focus:ring-2 focus:ring-yellow-300 focus:border-transparent dark:text-white dark:bg-black">
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Photo</label>
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        @if($profileImageUrl)
                                            <img src="{{ $profileImageUrl }}" alt="Profile"
                                                 class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                                                 id="form-avatar-preview">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-2 border-gray-200 dark:border-gray-600"
                                                 id="form-avatar-preview">
                                                <span class="text-xl font-bold text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <label for="profile-image-input" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-800  rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Choose Photo
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">JPG, PNG or GIF. Max 2MB.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cover Image</label>
                                <div class="flex items-center gap-4">
                                    <img id="profile-cover-preview"
                                         src="{{ $user->cover ? (str_starts_with($user->cover, 'http') ? $user->cover : asset('storage/' . $user->cover)) : '' }}"
                                         alt="Profile cover preview"
                                         class="w-40 h-20 rounded-lg object-cover {{ $user->cover ? '' : 'hidden' }}">
                                    <label for="profile-cover-input" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                        Choose Cover
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG or PNG. Max 5MB.</p>
                                </div>
                                @error('cover')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                                <textarea name="bio" rows="4" placeholder="Tell us about yourself, your experience, and what you're looking for..."
                                          class="w-full px-3 py-2 rounded-md focus:ring-2 focus:ring-yellow-300 focus: dark:text-white dark:bg-black">{{ old('bio', $user->bio) }}</textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ strlen($user->bio ?? '') }}/1000 characters</p>
                            </div>

                            <div class="flex gap-3 mt-6">
                                <button type="submit"
                                        class="bg-yellow-300 hover:bg-yellow-400 text-black font-medium py-2.5 px-6 rounded-lg transition duration-200 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Save Changes
                                </button>
                                <button type="reset"
                                        class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium py-2.5 px-6 rounded-lg transition duration-200">
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Work Experience Tab -->
                <div id="tab-experience" class="tab-content hidden">
                    <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-black dark:text-white">Work Experience</h2>
                            <button onclick="document.getElementById('add-experience-form').classList.toggle('hidden')"
                                    class="inline-flex items-center gap-1 text-yellow-500 hover:text-yellow-600 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Experience
                            </button>
                        </div>

                        <!-- Add Experience Form -->
                        <form method="POST" action="{{ route('user.work-experience.add') }}" id="add-experience-form" class="hidden mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg ">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company <span class="text-red-500">*</span></label>
                                    <input type="text" name="company_name" required placeholder="e.g. Google"
                                           class="w-full px-3 py-2 rounded-md focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Position <span class="text-red-500">*</span></label>
                                    <input type="text" name="position" required placeholder="e.g. Software Engineer"
                                           class="w-full px-3 py-2 rounded-md focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="start_date" required
                                           class="w-full px-3 py-2 rounded-md focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                                    <input type="date" name="end_date"
                                           class="w-full px-3 py-2 rounded-md focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave empty if current</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                <textarea name="description" rows="3" placeholder="Describe your responsibilities and achievements..."
                                          class="w-full px-3 py-2 rounded-md focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black"></textarea>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-yellow-300 hover:bg-yellow-400 text-black font-medium py-2 px-4 rounded-lg text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Experience
                                </button>
                                <button type="button" onclick="document.getElementById('add-experience-form').classList.add('hidden')"
                                        class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded-lg text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>

                        @if($workExperiences->count() > 0)
                            <div class="space-y-4">
                                @foreach($workExperiences as $experience)
                                    <div class=" rounded-lg p-4 hover:border-yellow-300 dark:hover:border-yellow-300 transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h3 class="font-medium text-black dark:text-white">{{ $experience->position }}</h3>
                                                    @if(!$experience->end_date)
                                                        <span class="text-xs px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full">Current</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $experience->company_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                    {{ $experience->start_date ? $experience->start_date->format('M Y') : '' }} —
                                                    {{ $experience->end_date ? $experience->end_date->format('M Y') : 'Present' }}
                                                    @if($experience->start_date)
                                                        <span class="text-gray-400">({{ $experience->start_date->diffForHumans($experience->end_date ?? now(), ['parts' => 2]) }})</span>
                                                    @endif
                                                </p>
                                                @if($experience->description)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $experience->description }}</p>
                                                @endif
                                            </div>
                                            <form method="POST" action="{{ route('user.work-experience.delete', $experience->id) }}" class="flex-shrink-0 ml-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 p-1 rounded transition"
                                                        onclick="return confirm('Delete this work experience?')"
                                                        title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-3">No work experience added yet.</p>
                                <button onclick="document.getElementById('add-experience-form').classList.remove('hidden')"
                                        class="text-yellow-500 hover:text-yellow-600 text-sm font-medium">
                                    + Add your first experience
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Skills Tab -->
                <div id="tab-skills" class="tab-content hidden">
                    <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-black dark:text-white mb-2">Skills</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Add skills to help employers find you. Click the × on a skill to remove it.</p>

                        <!-- Current Skills -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Your Skills</h3>
                            <div class="flex flex-wrap gap-2" id="skills-container">
                                @forelse($user->skills as $skill)
                                    <span class="skill-tag inline-flex items-center gap-1 px-3 py-1.5 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm transition hover:scale-105">
                                        {{ $skill->name }}
                                        <button onclick="removeSkill({{ $skill->id }}, this)" class="ml-1 w-4 h-4 flex items-center justify-center rounded-full bg-yellow-300 text-black hover:bg-yellow-400 transition text-xs font-bold leading-none">&times;</button>
                                    </span>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400" id="no-skills-msg">No skills added yet. Add some below!</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Add Skill -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Add New Skill</h3>
                            <div class="flex gap-2">
                                <input type="text" id="skill-input" placeholder="Type a skill and press Enter..."
                                       class="flex-1 px-3 py-2 rounded-md text-sm focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                                <button onclick="addSkill()" class="bg-yellow-300 hover:bg-yellow-400 text-black px-4 py-2 rounded-md text-sm font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add
                                </button>
                            </div>
                            <!-- Suggested Skills -->
                            @if(isset($allSkills) && $allSkills->count() > 0)
                                <div class="mt-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Suggested:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($allSkills->take(12) as $skill)
                                            @if(!$user->skills->contains($skill->id))
                                                <button onclick="addSuggestedSkill('{{ $skill->name }}')"
                                                        class="px-2.5 py-1 text-xs  text-gray-600 dark:text-gray-400 rounded-full hover:border-yellow-300 hover:text-yellow-500 transition">
                                                    + {{ $skill->name }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Change Password Tab -->
                <div id="tab-password" class="tab-content hidden">
                    <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-black dark:text-white mb-2">Change Password</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Ensure your account stays secure by using a strong password.</p>

                        <form method="POST" action="{{ route('user.change-password') }}" id="password-form" class="max-w-md">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="current_password" required
                                           class="w-full px-3 py-2 rounded-md text-sm focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="password" required minlength="8" id="new-password"
                                           class="w-full px-3 py-2 rounded-md text-sm focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 8 characters</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="password_confirmation" required id="confirm-password"
                                           class="w-full px-3 py-2 rounded-md text-sm focus:ring-2 focus:ring-yellow-300 dark:text-white dark:bg-black">
                                    <p class="text-xs mt-1" id="password-match-msg"></p>
                                </div>
                                <button type="submit" class="w-full bg-yellow-300 hover:bg-yellow-400 text-black font-medium py-2.5 px-4 rounded-lg text-sm flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Identity Verification Tab -->
                <div id="tab-verification" class="tab-content hidden">
                    <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-black dark:text-white mb-2">Identity Verification</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Verify your identity by uploading your National ID (NID) card. This helps build trust with employers.</p>

                        <!-- Current Status -->
                        <div class="mb-6 p-4 rounded-lg {{ $user->identity_verified == 1 ? 'border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20' : ($user->identity_verified == 2 ? 'border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-900/20' : 'border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    @if($user->identity_verified == 1)
                                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @elseif($user->identity_verified == 2)
                                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Status: <span class="font-semibold">{{ $user->identity_verification_status_label }}</span></p>
                                        @if($user->identity_verified == 2 && $user->identity_rejection_reason)
                                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">Reason: {{ $user->identity_rejection_reason }}</p>
                                        @endif
                                        @if($user->identity_verified == 1 && $user->identity_verified_at)
                                            <p class="text-xs text-green-600 dark:text-green-400 mt-1">Verified on {{ $user->identity_verified_at->format('M d, Y') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->identity_verification_status_color }}">
                                    {{ $user->identity_verification_status_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Upload Form -->
                        @if($user->identity_verified != 1)
                        <form method="POST" action="{{ route('user.identity.upload') }}" enctype="multipart/form-data" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NID Number <span class="text-red-500">*</span></label>
                                <input type="text" name="nid_number" value="{{ old('nid_number', $user->nid_number) }}" required placeholder="Enter your National ID number"
                                    class="w-full px-3 py-2  rounded-md focus:ring-2 focus:ring-yellow-300 focus:border-transparent dark:text-white dark:bg-black">
                                @error('nid_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NID Front Side <span class="text-red-500">*</span></label>
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:border-yellow-300 transition">
                                        @if($user->nid_front_path)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $user->nid_front_path) }}" alt="NID Front" class="max-h-32 mx-auto rounded">
                                                <p class="text-xs text-green-600 mt-1">✓ Uploaded</p>
                                            </div>
                                        @endif
                                        <input type="file" name="nid_front" accept="image/*,.pdf" {{ !$user->nid_front_path ? 'required' : '' }} class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-yellow-300 file:text-black hover:file:bg-yellow-400">
                                    </div>
                                    @error('nid_front')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NID Back Side <span class="text-red-500">*</span></label>
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:border-yellow-300 transition">
                                        @if($user->nid_back_path)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $user->nid_back_path) }}" alt="NID Back" class="max-h-32 mx-auto rounded">
                                                <p class="text-xs text-green-600 mt-1">✓ Uploaded</p>
                                            </div>
                                        @endif
                                        <input type="file" name="nid_back" accept="image/*,.pdf" {{ !$user->nid_back_path ? 'required' : '' }} class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-yellow-300 file:text-black hover:file:bg-yellow-400">
                                    </div>
                                    @error('nid_back')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                <p class="text-xs text-blue-700 dark:text-blue-300">
                                    <strong>📋 Guidelines:</strong> Upload clear, readable photos of both sides of your National ID card. Accepted formats: JPG, PNG, PDF. Maximum file size: 2MB each. Your documents will be reviewed by our admin team within 24-48 hours.
                                </p>
                            </div>

                            <button type="submit" class="bg-yellow-300 hover:bg-yellow-400 text-black font-medium py-2.5 px-6 rounded-lg transition duration-200 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Submit for Verification
                            </button>
                        </form>
                        @else
                        <div class="text-center py-6">
                            <svg class="w-16 h-16 text-green-500 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-lg font-medium text-green-600 dark:text-green-400">Identity Verified ✓</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Your identity has been verified. You can now apply to all jobs on the platform.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Profile Completion -->
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Profile Completion</h3>
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-600 dark:text-gray-400">Complete your profile</span>
                            <span class="font-medium text-black dark:text-white">{{ $user->profile_completion }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-yellow-300 h-2.5 rounded-full transition-all duration-500" style="width: {{ $user->profile_completion }}%"></div>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $user->name ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Personal information</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $user->bio ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Bio</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $user->image ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Profile photo</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $user->skills->count() > 0 ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011..414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Skills</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 {{ $user->workExperiences->count() > 0 ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Work experience</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Quick Links</h3>
                    <div class="space-y-3">
                        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition group">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-yellow-600 dark:group-hover:text-yellow-400">Dashboard</span>
                        </a>
                        <a href="{{ route('user.applications') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition group">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-yellow-600 dark:group-hover:text-yellow-400">My Applications</span>
                        </a>
                        <a href="{{ route('jobs') }}" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition group">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-black dark:text-white group-hover:text-yellow-600 dark:group-hover:text-yellow-400">Browse Jobs</span>
                        </a>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3">
                            <a href="{{ URL::to('/signout') }}" class="flex items-center gap-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="text-sm font-medium text-red-600 dark:text-red-400">Sign Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@section('js')
<script>
// Tab switching
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    // Show selected tab
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    // Update tab button styles
    document.querySelectorAll('.tab-btn').forEach(btn => {
        if (btn.dataset.tab === tabName) {
            btn.classList.add('border-yellow-500', 'text-yellow-500');
            btn.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        } else {
            btn.classList.remove('border-yellow-500', 'text-yellow-500');
            btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        }
    });
}

// Profile image preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Update header avatar
            const headerAvatar = document.getElementById('header-avatar');
            const headerPlaceholder = document.getElementById('header-avatar-placeholder');
            if (headerAvatar) {
                headerAvatar.src = e.target.result;
            } else if (headerPlaceholder) {
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.alt = 'Profile';
                newImg.className = 'w-24 h-24 rounded-full object-cover border-4 border-white';
                newImg.id = 'header-avatar';
                headerPlaceholder.replaceWith(newImg);
            }
            // Update form avatar preview
            const formPreview = document.getElementById('form-avatar-preview');
            if (formPreview) {
                if (formPreview.tagName === 'IMG') {
                    formPreview.src = e.target.result;
                } else {
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.alt = 'Profile';
                    newImg.className = 'w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600';
                    newImg.id = 'form-avatar-preview';
                    formPreview.replaceWith(newImg);
                }
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewCover(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function (event) {
        const preview = document.getElementById('profile-cover-preview');
        if (!preview) return;
        preview.src = event.target.result;
        preview.classList.remove('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

// Skills AJAX
function addSkill() {
    const input = document.getElementById('skill-input');
    const skillName = input.value.trim();
    if (!skillName) return;

    fetch('{{ route('user.skill.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ skill_name: skillName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(err => console.error('Error adding skill:', err));
}

function addSuggestedSkill(skillName) {
    fetch('{{ route('user.skill.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ skill_name: skillName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(err => console.error('Error adding skill:', err));
}

function removeSkill(skillId, btnElement) {
    if (!confirm('Remove this skill?')) return;

    fetch('{{ route('user.skill.remove') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ skill_id: skillId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animate removal
            const tag = btnElement.closest('.skill-tag');
            tag.style.transform = 'scale(0)';
            tag.style.opacity = '0';
            setTimeout(() => {
                tag.remove();
                // Check if no skills left
                const container = document.getElementById('skills-container');
                if (container.querySelectorAll('.skill-tag').length === 0) {
                    container.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400" id="no-skills-msg">No skills added yet. Add some below!</p>';
                }
            }, 200);
        }
    })
    .catch(err => console.error('Error removing skill:', err));
}

// Enter key for skill input
document.getElementById('skill-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addSkill();
    }
});

// Password match validation
const newPassword = document.getElementById('new-password');
const confirmPassword = document.getElementById('confirm-password');
const matchMsg = document.getElementById('password-match-msg');

function checkPasswordMatch() {
    if (!confirmPassword.value) {
        matchMsg.textContent = '';
        matchMsg.className = 'text-xs mt-1';
        return;
    }
    if (newPassword.value === confirmPassword.value) {
        matchMsg.textContent = '✓ Passwords match';
        matchMsg.className = 'text-xs mt-1 text-green-500';
    } else {
        matchMsg.textContent = '✗ Passwords do not match';
        matchMsg.className = 'text-xs mt-1 text-red-500';
    }
}

newPassword.addEventListener('input', checkPasswordMatch);
confirmPassword.addEventListener('input', checkPasswordMatch);

// Prevent form submission if passwords don't match
document.getElementById('password-form').addEventListener('submit', function(e) {
    if (newPassword.value !== confirmPassword.value) {
        e.preventDefault();
        confirmPassword.focus();
        matchMsg.textContent = '✗ Passwords do not match';
        matchMsg.className = 'text-xs mt-1 text-red-500 font-medium';
    }
});

// ===== Phone Verification =====
function sendPhoneCode() {
    const phone = document.querySelector('input[name="phone"]').value;
    if (!phone) {
        alert('Please enter a phone number first.');
        return;
    }

    fetch('{{ route("user.phone.send-code") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ phone: phone })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('phone-verify-section').classList.add('hidden');
            document.getElementById('phone-code-section').classList.remove('hidden');
            document.getElementById('phone-code-msg').textContent = data.message;
            document.getElementById('phone-code-msg').className = 'text-xs mt-1 text-green-500';
        } else {
            alert(data.message || 'Failed to send code.');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Failed to send verification code.');
    });
}

function verifyPhoneCode() {
    const code = document.getElementById('phone-code-input').value;
    if (code.length !== 6) {
        document.getElementById('phone-code-msg').textContent = 'Please enter a 6-digit code.';
        document.getElementById('phone-code-msg').className = 'text-xs mt-1 text-red-500';
        return;
    }

    fetch('{{ route("user.phone.verify") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ code: code })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('phone-code-msg').textContent = '✓ Phone verified successfully!';
            document.getElementById('phone-code-msg').className = 'text-xs mt-1 text-green-500 font-medium';
            setTimeout(() => location.reload(), 1500);
        } else {
            document.getElementById('phone-code-msg').textContent = data.message || 'Invalid code.';
            document.getElementById('phone-code-msg').className = 'text-xs mt-1 text-red-500';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Failed to verify code.');
    });
}
</script>
@endsection

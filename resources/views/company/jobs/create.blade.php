@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <!-- Header -->
    <div class="qj-dashboard-hero">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold mb-1">Post a New Job</h1>
            <p class="text-sm opacity-75">{{ $company->name }} — Job will be reviewed before publishing</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-3xl">

        <!-- Info Alert -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700 rounded-lg p-4 mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 000-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Jobs require admin approval</p>
                <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">Your job posting will be reviewed by our team and published once approved. You'll be notified of the status.</p>
            </div>
        </div>

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('company.jobs.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Job Title -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400 focus:ring-2 focus:ring-yellow-300"
                    placeholder="e.g. Senior Software Engineer">
            </div>

            <!-- Description & Requirements -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 space-y-4">
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="5" required
                        class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400 focus:ring-2 focus:ring-yellow-300"
                        placeholder="Describe the role, responsibilities, and what makes this opportunity exciting...">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="requirements" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="4"
                        class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400 focus:ring-2 focus:ring-yellow-300"
                        placeholder="Skills, qualifications, experience required...">{{ old('requirements') }}</textarea>
                </div>
                <div>
                    <label for="how_to_apply" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">How to Apply</label>
                    <textarea name="how_to_apply" id="how_to_apply" rows="2"
                        class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400 focus:ring-2 focus:ring-yellow-300"
                        placeholder="e.g. Send your CV to jobs@company.com">{{ old('how_to_apply') }}</textarea>
                </div>
            </div>

            <!-- Job Details Grid -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                <h3 class="text-base font-semibold text-black dark:text-white mb-4">Job Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="job_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Type <span class="text-red-500">*</span></label>
                        <select name="job_type_id" id="job_type_id" required
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                            <option value="">Select Type</option>
                            @foreach($jobTypes as $type)
                                <option value="{{ $type->id }}" {{ old('job_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="experience_level_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Experience Level <span class="text-red-500">*</span></label>
                        <select name="experience_level_id" id="experience_level_id" required
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                            <option value="">Select Level</option>
                            @foreach($experienceLevels as $level)
                                <option value="{{ $level->id }}" {{ old('experience_level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400"
                            placeholder="e.g. Addis Ababa, Ethiopia">
                    </div>
                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Salary</label>
                        <input type="text" name="salary" id="salary" value="{{ old('salary') }}"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400"
                            placeholder="e.g. ETB 15,000 - 25,000 / month">
                    </div>
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration (days)</label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration', 30) }}" min="1" max="365"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400"
                            placeholder="30">
                        <p class="text-xs text-gray-500 mt-1">How long this job stays active (default: 30 days)</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application Deadline</label>
                        <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}"
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                        <p class="text-xs text-gray-500 mt-1">Optional. Job will be hidden after this date.</p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-between">
                <a href="{{ route('company.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    ← Back to Dashboard
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-yellow-300 hover:bg-yellow-400 text-black font-semibold rounded-lg shadow-md transition">
                    Submit Job for Review
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

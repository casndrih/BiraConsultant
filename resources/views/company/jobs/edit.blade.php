@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <!-- Header -->
    <div class="bc-dashboard-hero">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold mb-1">Edit Job</h1>
            <div class="flex items-center gap-3">
                <p class="text-sm opacity-75">{{ $company->name }}</p>
                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $job->approvalStatusColor() }}">
                    {{ $job->approvalStatusLabel() }}
                </span>
                @if($job->approval_status === 'rejected' && $job->rejection_reason)
                    <span class="text-xs opacity-75">— {{ $job->rejection_reason }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-3xl">

        @if($job->approval_status === 'rejected')
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-yellow-300 dark:border-yellow-700 rounded-lg p-4 mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">This job was rejected</p>
                <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">Reason: {{ $job->rejection_reason ?? 'No reason provided.' }} Editing and saving will resubmit for review.</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-700 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('company.jobs.update', $job->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Job Title -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $job->title) }}" required
                    class="w-full px-4 py-2.5  rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
            </div>

            <!-- Description & Requirements -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 space-y-4">
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="5" required
                        class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400">{{ old('description', $job->description) }}</textarea>
                </div>
                <div>
                    <label for="requirements" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="4"
                        class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400">{{ old('requirements', $job->requirements) }}</textarea>
                </div>
                <div>
                    <label for="how_to_apply" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">How to Apply</label>
                    <textarea name="how_to_apply" id="how_to_apply" rows="2"
                        class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400">{{ old('how_to_apply', $job->how_to_apply) }}</textarea>
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
                                <option value="{{ $cat->id }}" {{ old('category_id', $job->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="job_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Type <span class="text-red-500">*</span></label>
                        <select name="job_type_id" id="job_type_id" required
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                            <option value="">Select Type</option>
                            @foreach($jobTypes as $type)
                                <option value="{{ $type->id }}" {{ old('job_type_id', $job->job_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="experience_level_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Experience Level <span class="text-red-500">*</span></label>
                        <select name="experience_level_id" id="experience_level_id" required
                            class="w-full px-4 py-2.5 rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                            <option value="">Select Level</option>
                            @foreach($experienceLevels as $level)
                                <option value="{{ $level->id }}" {{ old('experience_level_id', $job->experience_level_id) == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $job->location) }}"
                            class="w-full px-4 py-2.5  rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Salary</label>
                        <input type="text" name="salary" id="salary" value="{{ old('salary', $job->salary) }}"
                            class="w-full px-4 py-2.5  rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration (days)</label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration', $job->duration ?? 30) }}" min="1" max="365"
                            class="w-full px-4 py-2.5  rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application Deadline</label>
                        <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $job->deadline ? $job->deadline->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-2.5  rounded-lg bg-white dark:bg-gray-700 text-black dark:text-white focus:ring-2 focus:ring-yellow-400">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-between">
                <a href="{{ route('company.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    ← Back to Dashboard
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-yellow-400 hover:bg-yellow-500 text-black font-semibold rounded-lg shadow-md transition">
                    {{ $job->approval_status === 'rejected' ? 'Resubmit for Review' : 'Update Job' }}
                </button>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="mt-8 border-red-300 dark:border-red-700 rounded-lg p-6">
            <h3 class="text-base font-semibold text-red-600 mb-3">Danger Zone</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">Delete this job posting</p>
                    <p class="text-xs text-gray-500">This action cannot be undone. All applications will also be removed.</p>
                </div>
                <form action="{{ route('company.jobs.delete', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job? All applications will be lost.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                        Delete Job
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

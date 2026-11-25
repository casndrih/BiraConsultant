@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="qj-dashboard-page min-h-screen bg-gray-50 dark:bg-black">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-black dark:text-white mb-2">Company Verification</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">Submit your company registration and tax documents for verification.</p>

            @if(session('success'))
                <div class="border bg-green-100 dark:bg-green-900/30 border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="border bg-red-100 dark:bg-red-900/30 border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Current Status -->
            <div class="mb-6 p-4 border rounded-lg {{ $company->verification_status == 1 ? 'border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20' : ($company->verification_status == 2 ? 'border-red-300 dark:border-red-700 bg-red-50 dark:bg-red-900/20' : 'border-yellow-300 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/20') }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($company->verification_status == 1)
                            <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($company->verification_status == 2)
                            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 " fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Status: <span class="font-semibold">{{ $company->verification_status_label }}</span></p>
                            @if($company->verification_status == 2 && $company->verification_rejection_reason)
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">Reason: {{ $company->verification_rejection_reason }}</p>
                            @endif
                            @if($company->verification_status == 1 && $company->verified_at)
                                <p class="text-xs text-green-600 dark:text-green-400 mt-1">Verified on {{ $company->verified_at->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $company->verification_status_color }}">
                        {{ $company->verification_status_label }}
                    </span>
                </div>
            </div>

            @if($company->verification_status != 1)
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-8">
                <form method="POST" action="{{ route('company.verification.upload') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Registration Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Registration Number *</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number', $company->registration_number) }}" required placeholder="e.g. REG-12345"
                            class="w-full px-3 py-2  rounded-md focus:ring-2 focus:ring-yellow-300 focus:border-transparent dark:text-white dark:bg-black">
                        @error('registration_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Registration Document -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Registration Document *</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:border-yellow-300 transition">
                            @if($company->registration_doc_path)
                                <div class="mb-2">
                                    <svg class="w-8 h-8 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-xs text-green-600 mt-1">✓ Document uploaded</p>
                                </div>
                            @endif
                            <input type="file" name="registration_doc" accept=".pdf,image/*" {{ !$company->registration_doc_path ? 'required' : '' }} class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-yellow-300 file:text-black hover:file:bg-yellow-400">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, or PNG. Max 2MB.</p>
                        @error('registration_doc')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Tax ID Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tax ID Number *</label>
                        <input type="text" name="tax_id_number" value="{{ old('tax_id_number', $company->tax_id_number) }}" required placeholder="e.g. TIN-12345"
                            class="w-full px-3 py-2  rounded-md focus:ring-2 focus:ring-yellow-300 focus:border-transparent dark:text-white dark:bg-black">
                        @error('tax_id_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Tax Document -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tax Document *</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:border-yellow-300 transition">
                            @if($company->tax_doc_path)
                                <div class="mb-2">
                                    <svg class="w-8 h-8 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-xs text-green-600 mt-1">✓ Document uploaded</p>
                                </div>
                            @endif
                            <input type="file" name="tax_doc" accept=".pdf,image/*" {{ !$company->tax_doc_path ? 'required' : '' }} class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-yellow-300 file:text-black hover:file:bg-yellow-400">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, or PNG. Max 2MB.</p>
                        @error('tax_doc')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="border bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 rounded-lg p-3">
                        <p class="text-xs text-blue-700 dark:text-blue-300">
                            <strong>📋 Guidelines:</strong> Upload your company registration certificate and tax identification document. Accepted formats: PDF, JPG, PNG. Maximum file size: 2MB each. Your documents will be reviewed by our admin team within 24-48 hours.
                        </p>
                    </div>

                    <button type="submit" class="w-full bg-yellow-300 hover:bg-yellow-400 text-black font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Submit for Verification
                    </button>
                </form>
            </div>
            @else
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-8 text-center">
                <svg class="w-16 h-16 text-green-500 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-lg font-medium text-green-600 dark:text-green-400">Company Verified ✓</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Your company has been verified. You can now post jobs on the platform.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

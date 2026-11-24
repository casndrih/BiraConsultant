@extends('admin.app')

@section('content')
<section class="bg-gray-100 dark:bg-black py-3">
    {{-- Page Header --}}
    <div class="mx-4 mb-4">
        <h1 class="text-2xl font-bold text-black dark:text-white">Company Verification</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review and approve or reject company verification submissions.</p>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="mx-4 mb-4">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                <li class="mr-2">
                    <a href="{{ route('admin.companies.verification', ['tab' => 'pending']) }}"
                        class="inline-block p-4 border-b-2 {{ $activeTab === 'pending' ? 'border-yellow-400 text-yellow-400 dark:text-yellow-400 dark:border-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} rounded-t-lg">
                        Pending
                        @if($pendingCount > 0)
                            <span class="ml-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-300 text-black">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="mr-2">
                    <a href="{{ route('admin.companies.verification', ['tab' => 'verified']) }}"
                        class="inline-block p-4 border-b-2 {{ $activeTab === 'verified' ? 'border-green-500 text-green-500 dark:text-green-400 dark:border-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} rounded-t-lg">
                        Verified
                    </a>
                </li>
                <li class="mr-2">
                    <a href="{{ route('admin.companies.verification', ['tab' => 'rejected']) }}"
                        class="inline-block p-4 border-b-2 {{ $activeTab === 'rejected' ? 'border-red-500 text-red-500 dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }} rounded-t-lg">
                        Rejected
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Verification Cards --}}
    <div class="mx-4 space-y-4">
        @forelse ($companies as $company)
        <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md overflow-hidden" id="verification-card-{{ $company->id }}">
            <div class="p-5">
                <div class="flex flex-col lg:flex-row lg:items-start gap-5">
                    {{-- Company Info --}}
                    <div class="flex-shrink-0 lg:w-72">
                        <div class="flex items-center mb-3">
                            <img class="w-12 h-12 border-gray-200 dark:border-custom-dark rounded-lg object-cover"
                                src="{{ $company->logo ?? asset('/images/users/userpro.png') }}"
                                alt="{{ $company->name }} logo">
                            <div class="ml-3">
                                <div class="text-base font-semibold text-black dark:text-white">{{ $company->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->industry ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="space-y-1 text-sm">
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-black dark:text-white">Reg. Number:</span>
                                {{ $company->registration_number ?? 'N/A' }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-black dark:text-white">Tax ID:</span>
                                {{ $company->tax_id_number ?? 'N/A' }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-black dark:text-white">Location:</span>
                                {{ $company->location ?? 'N/A' }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">
                                <span class="font-medium text-black dark:text-white">Submitted:</span>
                                {{ $company->updated_at->format('Y M d, h:i A') }}
                            </p>
                            <p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $company->verification_status_color }}">
                                    {{ $company->verification_status_label }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Documents --}}
                    <div class="flex-1">
                        <p class="text-sm font-medium text-black dark:text-white mb-2">Uploaded Documents</p>
                        <div class="flex flex-wrap gap-3">
                            {{-- Registration Document --}}
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-custom-dark rounded-lg ">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-black dark:text-white">Registration Document</p>
                                    @if($company->registration_doc_path)
                                        <a href="{{ asset($company->registration_doc_path) }}" target="_blank" rel="noopener noreferrer"
                                            class="text-sm text-yellow-500 hover:text-yellow-600 dark:text-yellow-400 dark:hover:text-yellow-300 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Download
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-400">Not uploaded</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Tax Document --}}
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-custom-dark rounded-lg ">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-black dark:text-white">Tax Document</p>
                                    @if($company->tax_doc_path)
                                        <a href="{{ asset($company->tax_doc_path) }}" target="_blank" rel="noopener noreferrer"
                                            class="text-sm text-yellow-500 hover:text-yellow-600 dark:text-yellow-400 dark:hover:text-yellow-300 inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Download
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-400">Not uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Company Owner Info --}}
                        @if($company->user)
                        <div class="mt-4 p-3 bg-gray-50 dark:bg-custom-dark rounded-lg ">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Company Owner</p>
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full object-cover"
                                    src="{{ $company->user->image ?? asset('/images/users/userpro.png') }}"
                                    alt="{{ $company->user->name }}">
                                <div class="ml-2">
                                    <p class="text-sm font-medium text-black dark:text-white">{{ $company->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $company->user->email }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex-shrink-0 lg:w-64">
                        @if((int) $company->verification_status === 0)
                            {{-- Approve Button --}}
                            <form action="{{ route('admin.companies.verification.approve', $company->id) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full text-black bg-yellow-300 hover:bg-yellow-400 font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve
                                </button>
                            </form>

                            {{-- Reject Button --}}
                            <button type="button"
                                onclick="openRejectModal('company', {{ $company->id }}, '{{ addslashes($company->name) }}')"
                                class="w-full text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject
                            </button>
                        @elseif((int) $company->verification_status === 1)
                            <div class="text-green-500 flex items-center mb-2 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Company Verified
                            </div>
                            @if($company->verified_at)
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Verified on {{ $company->verified_at->format('Y M d, h:i A') }}
                                </p>
                            @endif
                        @elseif((int) $company->verification_status === 2)
                            <div class="text-red-500 flex items-center mb-2 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Verification Rejected
                            </div>
                            @if($company->verification_rejection_reason)
                                <div class="mt-2 p-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                    <p class="text-xs text-red-700 dark:text-red-400">
                                        <span class="font-medium">Reason:</span> {{ $company->verification_rejection_reason }}
                                    </p>
                                </div>
                            @endif
                            {{-- Allow re-review --}}
                            <form action="{{ route('admin.companies.verification.approve', $company->id) }}" method="POST" class="mt-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full text-black bg-yellow-300 hover:bg-yellow-400 font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                    Approve Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-10 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-black dark:text-white">
                @if($activeTab === 'pending')
                    No pending verifications
                @elseif($activeTab === 'verified')
                    No verified companies yet
                @else
                    No rejected verifications
                @endif
            </h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                @if($activeTab === 'pending')
                    All company verification requests have been reviewed.
                @elseif($activeTab === 'verified')
                    Companies that have been verified will appear here.
                @else
                    Rejected verification requests will appear here.
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($companies->hasPages())
    <div class="mx-4 mt-4">
        {{ $companies->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</section>

{{-- Rejection Modal --}}
<div id="rejectModal" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
    <div class="relative w-full max-w-md max-h-full m-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-custom-dark">
            <button type="button"
                class="absolute top-3 right-2.5 text-black dark:text-white bg-transparent hover:text-yellow-300 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                onclick="closeRejectModal()">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
            <div class="p-6">
                <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">
                    Reject Company Verification
                </h3>
                <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to reject <span id="rejectCompanyName" class="font-bold text-black dark:text-white"></span>'s verification?
                </p>
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="rejection_reason" class="block mb-2 text-sm font-medium text-black dark:text-white">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3"
                            class="w-full px-3 py-2 text-sm rounded-lg bg-white dark:bg-black text-black dark:text-white focus:ring-2 focus:ring-yellow-300 focus:ring-2 focus:ring-yellow-300"
                            placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button"
                            class="px-5 py-2 text-sm font-medium text-black bg-gray-100 hover:bg-gray-200 rounded-full"
                            onclick="closeRejectModal()">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let currentRejectCompanyId = null;

    function openRejectModal(type, companyId, companyName) {
        currentRejectCompanyId = companyId;
        document.getElementById('rejectCompanyName').textContent = companyName;
        document.getElementById('rejectForm').action = `/admin/companies/${companyId}/verification/reject`;
        document.getElementById('rejection_reason').value = '';
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        currentRejectCompanyId = null;
    }

    // Close modal on outside click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>
@endsection

@extends('admin.app')

@section('content')
<section class="bg-gray-100 dark:bg-black py-2">
    <div class="relative overflow-x-auto mx-4 shadow-md rounded-t-lg">
        <div class="flex items-center justify-between py-4 bg-white dark:bg-custom-dark">
            <label for="table-search" class="sr-only">Search Job</label>
            <div class="ml-4 relative flex items-center">
                <div class="absolute inset-y-0 left-0 flex items-center ml-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-600" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <form action="{{ route('admin.job.search') }}" method="GET">
                    <input type="text" id="query-job" name="query-job"
                        value="{{ request()->query('query-job') }}"
                        class="block w-full p-2 pl-10 text-base text-gray-900  rounded-full bg-gray-100 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300"
                        placeholder="Search Job">
                </form>
            </div>

            <div class="flex items-center">
                <button id="add-job" type="button" data-modal-target="createJobModal"
                    data-modal-show="createJobModal"
                    class="text-white bg-black font-medium rounded-full text-md px-5 py-2.5 text-center ml-4 mr-4">New</button>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mx-4 mt-2">
        <div class="flex flex-wrap items-center gap-2 bg-white dark:bg-custom-dark rounded-lg p-2 shadow-sm">
            <a href="{{ route('admin.jobs.index', ['filter' => 'all']) }}"
                class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ $filter === 'all' ? 'bg-black text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.jobs.index', ['filter' => 'pending']) }}"
                class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ $filter === 'pending' ? 'bg-yellow-400 text-black' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Pending ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.jobs.index', ['filter' => 'approved']) }}"
                class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ $filter === 'approved' ? 'bg-green-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Approved ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('admin.jobs.index', ['filter' => 'rejected']) }}"
                class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ $filter === 'rejected' ? 'bg-red-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}>
                Rejected ({{ $counts['rejected'] }})
            </a>
            <a href="{{ route('admin.jobs.index', ['filter' => 'expired']) }}"
                class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ $filter === 'expired' ? 'bg-gray-500 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}>
                Expired ({{ $counts['expired'] }})
            </a>
        </div>
    </div>


    <div class="overflow-x-auto mx-4 shadow-md rounded-b-lg">
        <table class="w-full text-sm text-left text-black">
            <thead class="text-md text-black dark:text-white font-medium uppercase bg-white dark:bg-custom-dark">
                <tr>
                    <th class="px-6 py-4">Company</th>
                    <th class="px-6 py-4">Job Title</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Deadline</th>
                    <th class="px-6 py-4">Posted</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($jobs->count() > 0)
                    @foreach ($jobs as $job)
                        <tr id="{{ $job->id }}" class="bg-gray-50 dark:bg-black hover:bg-gray-100 dark:hover:bg-custom-dark whitespace-nowrap cursor-pointer"
                            data-id="{{ $job->id }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="w-auto h-6 mr-2"
                                    src="{{ $job->company->logo ?? asset('/images/users/userpro.png') }}"
                                    alt="Company image">
                                    <div class="text-base font-semibold text-black dark:text-white">
                                        {{ $job->company->name ?? 'Unknown Company' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-base text-black dark:text-white font-medium">{{ $job->title }}</td>
                            <td class="px-6 py-4 text-base text-black dark:text-white font-medium">{{ $job->jobType->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $job->approvalStatusColor() }}">
                                    {{ $job->approvalStatusLabel() }}
                                </span>
                                @if($job->isExpired())
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium text-gray-600 bg-gray-200 ml-1">Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-base text-black dark:text-white font-medium">
                                {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('Y-m-d') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-base text-black dark:text-white font-medium">
                                {{ $job->created_at->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1 flex-wrap">
                                    @if($job->approval_status === 'pending')
                                        <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-full">
                                                Approve
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $job->id }}, '{{ addslashes($job->title) }}')"
                                            class="px-3 py-1 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full">
                                            Reject
                                        </button>
                                    @elseif($job->approval_status === 'rejected')
                                        <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-full">
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                    <button onclick="openEditJobModal('{{ $job->slug }}')"
                                        class="px-3 py-1 text-sm font-medium text-black bg-yellow-300 hover:bg-yellow-400 rounded-full">
                                        Edit
                                    </button>
                                    <button onclick="confirmDeleteJob({{ $job->id }}, '{{ addslashes($job->title) }}')"
                                        class="px-3 py-1 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-lg font-medium text-center text-gray-900">
                            No jobs found.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="mx-4 my-4">
        {{ $jobs->links('vendor.pagination.tailwind') }}
    </div>

    @include('admin.jobs.modal.job-create-modal')
    @include('admin.jobs.modal.job-edit-modal')

    <!-- Reject Modal -->
    <div id="rejectModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-custom-dark sm:p-5">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Reject Job</h3>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Rejecting: <span id="rejectJobTitle" class="font-medium text-black dark:text-white"></span></p>
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="rejection_reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reason for rejection <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                            class="w-full px-3 py-2 text-sm rounded-lg bg-gray-100 dark:bg-black dark:text-white focus:ring-red-500 focus:border-red-500"
                            placeholder="Explain why this job is being rejected..."></textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                            Reject Job
                        </button>
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 rounded-lg">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function () {
    window.openEditJobModal = function(jobSlug) {
        $.ajax({
            url: `/admin/job/edit/${jobSlug}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                openEditModal(response);
            },
            error: function (xhr) {
                console.error('Failed to fetch job data:', xhr);
                alert('Unable to fetch job data. Please try again.');
            }
        });
    };

    function openEditModal(response) {
        const job = response.job;
        const jobTypes = response.jobTypes;
        const experienceLevels = response.experienceLevels;

        $('#edit_job_id').val(job.id);
        $('#edit_title').val(job.title ?? '');
        $('#edit_description').val(job.description ?? '');
        $('#edit_location').val(job.location ?? '');
        $('#edit_salary').val(job.salary ?? '');
        $('#edit_deadline').val(job.deadline ?? '');
        $('#edit_status').val(job.status ?? '');

        if (job.status == 1) {
            $('#edit_status_toggle').prop('checked', true);
            $('#edit_status').val(1);
        } else {
            $('#edit_status_toggle').prop('checked', false);
            $('#edit_status').val(0);
        }

        const typeSelect = $('#edit_type');
        typeSelect.empty();
        typeSelect.append(`<option value="">Select Job Type</option>`);
        jobTypes.forEach(type => {
            const selected = (type.id === job.job_type_id) ? 'selected' : '';
            typeSelect.append(`<option value="${type.id}" ${selected}>${type.name}</option>`);
        });

        const experienceSelect = $('#edit_experience');
        experienceSelect.empty();
        experienceSelect.append(`<option value="">Select Experience Level</option>`);
        experienceLevels.forEach(exp => {
            const selected = (exp.id === job.experience_level_id) ? 'selected' : '';
            experienceSelect.append(`<option value="${exp.id}" ${selected}>${exp.name}</option>`);
        });

        $('#delete_job_id').val(job.id);
        $('#edit_company_logo').attr('src', job.company?.logo ?? '');

        const modal = document.getElementById('editJobModal');
        const modalInstance = new Modal(modal);
        modalInstance.show();
    }

    window.closeEditModal = function () {
        const modal = document.getElementById('editJobModal');
        const modalInstance = Modal.getInstance(modal);
        modalInstance.hide();
    };

    $('#editModalCloseBtn, #editModalCancelBtn').on('click', function() {
        window.closeEditModal();
    });

    $('#edit_status_toggle').on('change', function () {
        $('#edit_status').val(this.checked ? 1 : 0);
    });

    // Delete confirmation
    window.confirmDeleteJob = function(jobId, jobTitle) {
        if (confirm('Are you sure you want to delete "' + jobTitle + '"?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.job.delete') }}';
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            const jobIdInput = document.createElement('input');
            jobIdInput.type = 'hidden';
            jobIdInput.name = 'delete_job_id';
            jobIdInput.value = jobId;
            form.appendChild(jobIdInput);
            document.body.appendChild(form);
            form.submit();
        }
    };

    window.openRejectModal = function(jobId, jobTitle) {
        document.getElementById('rejectJobTitle').textContent = jobTitle;
        document.getElementById('rejectForm').action = '/admin/jobs/' + jobId + '/reject';
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    };

    window.closeRejectModal = function() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
        document.getElementById('rejection_reason').value = '';
    };
});
</script>
@endsection

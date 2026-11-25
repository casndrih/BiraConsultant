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
                        @if($job->company) @include('components.company-popover', ['company' => $job->company]) @else Unknown Company @endif
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
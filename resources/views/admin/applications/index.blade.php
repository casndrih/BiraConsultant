@extends('admin.app')

@section('content')
    @php($fallbackUserAvatars = [
        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=240&h=240&q=85',
        'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=240&h=240&q=85',
        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=240&h=240&q=85',
        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=240&h=240&q=85',
        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=240&h=240&q=85',
        'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=240&h=240&q=85',
        'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&w=240&h=240&q=85',
        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=240&h=240&q=85'
    ])
    <section class="bg-gray-100 dark:bg-black py-2">
        <div class="qj-admin-applications-table-card mx-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="qj-admin-applications-toolbar flex flex-col gap-3 border-b border-gray-200 px-4 py-3 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Job applications</h2>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Search and review submitted applications</p>
                </div>
                <form class="qj-admin-applications-search-form flex w-full min-w-0 sm:w-auto sm:min-w-[280px] sm:max-w-md" action="{{ route('admin.application.search') }}" method="GET" role="search">
                    <label for="query-application" class="sr-only">Search applications</label>
                    <div class="relative w-full min-w-0">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input type="search" id="query-application" name="query-application"
                            value="{{ request()->query('query-application') }}"
                            class="block h-10 w-full rounded-lg border border-gray-300 bg-gray-50 py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-500 focus:border-yellow-400 focus:ring-yellow-400 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                            placeholder="Search applications..." autocomplete="off">
                    </div>
                    <button type="submit" class="ml-2 inline-flex h-10 shrink-0 items-center rounded-lg bg-yellow-400 px-4 text-sm font-medium text-gray-900 hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:focus:ring-yellow-800">Search</button>
                </form>
            </div>
            <div class="qj-admin-applications-table-wrap relative overflow-x-auto">
                <table class="w-full text-sm text-left text-black qj-admin-data-table qj-admin-data-table--applications">
                <thead class="text-md text-black dark:text-white font-medium uppercase bg-white dark:bg-custom-dark">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Company</th>
                        <th class="px-6 py-4">Job title</th>
                        <th class="px-6 py-4">Date Applied</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $application)
                        <tr id="{{ $application->id }}" class="bg-gray-50 dark:bg-black hover:bg-gray-100 dark:hover:bg-custom-dark whitespace-nowrap cursor-pointer"
                            data-href="{{ $application->id }}">
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900">
                                @if ($application->user)
                                    <img class="w-10 h-10 border-2 border-gray-200 dark:border-custom-dark rounded-full"
                                        src="{{ $application->user->image ?: $fallbackUserAvatars[$application->user->id % count($fallbackUserAvatars)] }}"
                                        alt="User image" onerror="this.onerror=null;this.src='{{ $fallbackUserAvatars[$application->user->id % count($fallbackUserAvatars)] }}';">
                                    <div class="pl-3">
                                        <div class="text-base md:text-lg font-semibold text-black dark:text-white">{{ $application->user->name }}</div>
                                        <div class="text-md font-normal text-gray-500">{{ $application->user->email }}</div>
                                    </div>
                                @else
                                    <div class="pl-3">
                                        <div class="text-base font-semibold text-black dark:text-white">Unknown User</div>
                                        <div class="font-normal text-gray-500">No email available</div>
                                    </div>
                                @endif
                            </th>

                            <td class="px-6 py-4">
                                @if ($application->job && $application->job->company)
                                    <div class="flex items-center gap-2">
                                       <div class="pl-3">
                                        <img class="w-auto h-6"
                                            src="{{ $application->job->company->logo ?? asset('/images/providers/default.png') }}"
                                            alt="Company image" onerror="this.onerror=null;this.src='{{ asset('/images/providers/default.png') }}';">
                                        <p class="text-base text-black dark:text-white font-medium">
                                            @include('components.company-popover', ['company' => $application->job->company])</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center gap-2">
                                        <p class="text-base text-black dark:text-white font-medium">No Company Available</p>
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-base text-black dark:text-white font-medium">{{ $application->job->title ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-base text-black dark:text-white font-medium">{{ $application->created_at->format('Y-m-d') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 mr-2 mb-2 text-sm font-medium text-black bg-yellow-300 rounded-full">
                               {{ $application->status->label ?? 'Unknown' }}
                                </span>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-lg font-medium text-center text-black dark:text-white">
                                No Applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>

        <div class="mx-4 mt-4">{{ $applications->links('vendor.pagination.tailwind') }}</div>
        @include('admin.applications.modal.application-modal')
    </section>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const fallbackUserAvatars = [
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=240&h=240&q=85',
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=240&h=240&q=85',
            'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=240&h=240&q=85',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=240&h=240&q=85',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=240&h=240&q=85',
            'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=240&h=240&q=85',
            'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&w=240&h=240&q=85'
        ];
        const rows = document.querySelectorAll('tr[data-href]');
        rows.forEach(row => {
            row.addEventListener('click', () => {
                const id = row.dataset.href;
                $.ajax({
                    url: `/admin/application/edit/${id}`,
                    type: "GET",
                    success: function (data) {
                        $('#view_application_id').val(data.id);
                        $('#view_application_userId').val(data.user?.id || '');
                        $('#view_application_jobId').val(data.job?.id || '');

                        $('#view_application_job_title').val(data.job?.title || 'N/A');
                        $('#view_application_company').val(data.job?.company?.name || 'N/A');
                        $('#edit_application_status').val(data.status?.id || '');
                        
                        const formattedDate = new Date(data.created_at).toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'short', // e.g., "May"
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                        });
                        $('#view_application_sub_date').text(formattedDate);

                        const userImage = $('#view_user_image');
                        const fallbackAvatar = fallbackUserAvatars[(Number(data.user?.id || 0)) % fallbackUserAvatars.length];
                        userImage.off('error').on('error', function () {
                            this.onerror = null;
                            this.src = fallbackAvatar;
                        }).attr('src', data.user?.image || fallbackAvatar);
                        $('#view_user_name').text(data.user?.name || 'Unknown');
                        $('#view_user_email').text(data.user?.email || 'Unknown');
                        
                        // Set resume link (always shown)
                        $('#resume_button_grid').attr('href', data.resume);
                        $('#resume_button_alone').attr('href', data.resume);

                        // Set cover letter preview and layout
                        if (data.cover_letter) {
                        // Show the grid with both Resume and Cover Letter
                        $('#cover_letter_button').attr('href', data.cover_letter);
                        $('#attachment_grid').removeClass('hidden');
                        $('#resume_alone_wrapper').addClass('hidden');
                        $('#cover_letter_placeholder').addClass('hidden');
                        } else {
                         // Show only Resume button centered
                        $('#attachment_grid').addClass('hidden');
                        $('#resume_alone_wrapper').removeClass('hidden');
                        $('#cover_letter_placeholder').removeClass('hidden');
                        }
                    },
                    complete: function () {
                        const modalElement = document.getElementById('viewApplicationModal');
                        const modal = new Modal(modalElement, {
                            placement: 'center',
                            backdrop: 'dynamic',
                            closable: true,
                        });
                        modal.show();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error:", textStatus, errorThrown);
                        alert('Error retrieving data. Please try again later.');
                    }
                });
            });
        });
    });
</script>
@endsection
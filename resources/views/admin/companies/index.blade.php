@extends('admin.app')

@section('content')
<section class="bg-gray-100 dark:bg-black py-2">
    <div class="qj-admin-companies-table-card mx-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="qj-admin-companies-toolbar">
        <div class="qj-admin-companies-toolbar-inner flex flex-wrap items-center justify-between gap-3 px-4 py-3 bg-white dark:bg-custom-dark">
            <div class="qj-admin-search relative flex min-w-0 flex-1 items-center">
                
                <form class="qj-admin-search__form relative" action="{{ route('admin.company.search') }}" method="GET">
                    <div class="absolute inset-y-0 left-0 flex items-center ml-4 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                              clip-rule="evenodd"></path>
                    </svg>
                </div><input type="text" id="query-company" name="query-company"
                           value="{{ request()->query('query-company') }}"
                           class="block w-full p-2 !pl-12 text-base text-gray-900  rounded-full bg-gray-100 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300"
                           placeholder="Search Company">
                </form>
            </div>

            <div class="flex min-w-0 items-center">
                <button type="button" data-modal-target="createCompanyModal"
                    data-modal-show="createCompanyModal"
                    class="text-white bg-black font-medium rounded-full text-md px-5 py-2.5">
                    New
                </button>
            </div>
        </div>
    </div>

    <div class="qj-admin-companies-table-wrap">
        <table aria-label="Registered companies" class="w-full min-w-[820px] text-sm text-left text-black qj-admin-data-table qj-admin-data-table--companies">
            <thead class="text-md text-black dark:text-white font-medium uppercase bg-white dark:bg-custom-dark">
            <tr>
                <th class="px-6 py-4">Logo</th>
                <th class="px-6 py-4">Name</th>
                <th class="px-6 py-4">Industry</th>
                <th class="px-6 py-4">Location</th>
                <th class="px-6 py-4">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($companies as $company)
                <tr class="bg-gray-50 dark:bg-black hover:bg-gray-100 dark:hover:bg-custom-dark"
                    data-id="{{ $company->id }}">
                    <td class="px-auto py-4">
                        <img class="w-auto h-6 ml-4" src="{{ $company->logo_url ?? asset('/images/users/userpro.png') }}" alt="Company Logo" onerror="this.onerror=null;this.src='{{ asset('/images/providers/default.png') }}';">
                    </td>
                    <td class="px-6 py-4 text-base font-medium text-black dark:text-white">{{ $company->name }}</td>
                    <td class="px-6 py-4 text-base font-medium text-black dark:text-white">{{ $company->industry ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-base font-medium text-black dark:text-white">{{ $company->location ?? 'N/A' }}</td>
                    <td class="px-4 py-4">
                        <div class="qj-admin-table-actions">
                            <button type="button" onclick="openEditCompanyModal('{{ $company->slug }}')" class="qj-admin-action-btn text-sm font-medium text-black bg-yellow-300 hover:bg-yellow-400 rounded-full">Edit</button>
                            <button type="button" onclick="confirmDeleteCompany({{ $company->id }}, @js($company->name))" class="qj-admin-action-btn text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full">Delete</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-lg text-gray-700">No Companies found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <div class="mx-4 my-4">
        {{ $companies->links('vendor.pagination.tailwind') }}
    </div>

    @include('admin.companies.modal.company-create-modal')
    @include('admin.companies.modal.company-edit-modal')

    {{-- Delete confirmation modal --}}
    <div id="deleteCompanyModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
        <div class="relative w-full max-w-md max-h-full m-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-custom-dark">
                <button type="button"
                    class="absolute top-3 right-2.5 text-black dark:text-white bg-transparent hover:text-yellow-300 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-hide="deleteCompanyModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="p-6 text-center">
                    <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-black dark:text-white">
                        Are you sure you want to delete <span id="deleteCompanyName" class="font-bold"></span>?
                    </h3>
                    <form id="deleteCompanyForm" method="GET">
                        <button type="submit"
                            class="px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full mr-2">
                            Yes, delete it
                        </button>
                        <button type="button"
                            class="px-5 py-2 text-sm font-medium text-black bg-gray-100 hover:bg-gray-200 rounded-full"
                            data-modal-hide="deleteCompanyModal">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    // Create modal
    $(document).on('click', '[data-modal-show="createCompanyModal"]', function () {
        const modal = document.getElementById('createCompanyModal');
        const modalInstance = new Modal(modal);
        modalInstance.show();
    });

    $(document).on('click', '[data-modal-hide="createCompanyModal"]', function () {
        const modal = document.getElementById('createCompanyModal');
        const modalInstance = Modal.getInstance(modal);
        if (modalInstance) modalInstance.hide();
    });

    // Edit modal
    window.openEditCompanyModal = function (companySlug) {
        $.ajax({
            url: `/admin/company/edit/${companySlug}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                populateEditCompanyModal(response);
            },
            error: function () {
                alert('Unable to fetch company data. Please try again.');
            }
        });
    };

    function populateEditCompanyModal(data) {
        $('#edit_company_id').val(data.id);
        $('#edit_company_name').val(data.name ?? '');
        $('#edit_company_website').val(data.website ?? '');
        $('#edit_company_logo').val(data.logo ?? '');
        $('#edit_company_industry').val(data.industry ?? '');
        $('#edit_company_location').val(data.location ?? '');
        $('#edit_company_email').val(data.email ?? '');
        $('#edit_company_phone').val(data.phone ?? '');
        $('#edit_company_founded').val(data.founded_year ?? '');
        $('#edit_company_size').val(data.size ?? '');
        $('#edit_company_description').val(data.description ?? '');

        const modal = document.getElementById('editCompanyModal');
        const modalInstance = new Modal(modal);
        modalInstance.show();
    }

    $(document).on('click', '[data-modal-hide="editCompanyModal"]', function () {
        const modal = document.getElementById('editCompanyModal');
        const modalInstance = Modal.getInstance(modal);
        if (modalInstance) modalInstance.hide();
    });

    // Delete confirmation
    window.confirmDeleteCompany = function (companySlug, companyName) {
        $('#deleteCompanyName').text(companyName);
        $('#deleteCompanyForm').attr('action', `/admin/company/delete/${companySlug}`);

        const modal = document.getElementById('deleteCompanyModal');
        const modalInstance = new Modal(modal);
        modalInstance.show();
    };

    $(document).on('click', '[data-modal-hide="deleteCompanyModal"]', function () {
        const modal = document.getElementById('deleteCompanyModal');
        const modalInstance = Modal.getInstance(modal);
        if (modalInstance) modalInstance.hide();
    });
</script>
@endsection

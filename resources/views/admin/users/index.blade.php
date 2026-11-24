@extends('admin.app')

@section('content')
    <section class="bg-gray-100 dark:bg-black py-2">
        <!-- Search -->
        <div class="relative overflow-x-auto mx-4 shadow-md rounded-t-lg">
            <div class="flex items-center justify-between py-4 bg-white dark:bg-custom-dark">
                    <label for="search" class="sr-only">Search User</label>
                    <div class="qj-admin-search ml-4 relative flex items-center">
                    
                     
                    <form class="qj-admin-search__form relative" action="{{ route('admin.user.search') }}" method="GET">
                    <div class="absolute inset-y-0 left-0 flex items-center ml-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div><input type="text" name="search"
                        class="block w-full p-2 !pl-12 text-base text-gray-900 rounded-full bg-gray-100 dark:bg-black focus:ring-yellow-300 focus:ring-2 focus:ring-yellow-300"
                        placeholder="Search User">
                </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto mx-4 shadow-md rounded-b-lg">
            <table class="w-full text-left text-black dark:text-white qj-admin-data-table qj-admin-data-table--users">
                <thead class="text-md font-medium uppercase bg-white dark:bg-custom-dark">
                    <tr>
                        <th class="px-6 py-4">Users</th>
                        <th class="px-6 py-4">Skills</th>
                        <th class="px-6 py-4">Birthday</th>
                        <th class="px-6 py-4">Gender</th>
                        <th class="px-6 py-4">Country</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Website</th>
                        <th class="px-6 py-4">Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr data-href="{{ $user->slug }}" class="bg-gray-50 dark:bg-black hover:bg-gray-100 dark:hover:bg-custom-dark cursor-pointer">
                            <td class="flex items-center px-6 py-4 whitespace-nowrap">
                                <img class="w-10 h-10 border-gray-200 dark:border-custom-dark rounded-full object-cover"
                                    src="{{ $user->image ?? asset('/images/users/userpro.png') }}"
                                    alt="{{ $user->name }} image" onerror="this.onerror=null;this.src='{{ asset('/images/users/userpro.png') }}';">
                                <div class="pl-3">
                                    <div class="text-base font-semibold">{{ $user->name }}</div>
                                    <div class="font-normal">{{ $user->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-base font-medium">{{ $user->skills->count() }}</td>
                            <td class="px-6 py-4 text-base font-medium">{{ $user->birthday }}</td>
                            <td class="px-6 py-4 text-base font-medium capitalize">
                                @if ($user->gender == 0)
                                    <p class="">Male</p>
                                @else
                                    <p class="">Female</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p
                                    class="inline-flex line-clamp-2 items-center text-base font-nunito font-medium">
                                    @if (
                                        $user->country &&
                                            file_exists(public_path('vendor/blade-flags/country-' . strtolower($user->country->iso) . '.svg')))
                                        <img src="{{ asset('vendor/blade-flags/country-' . strtolower($user->country->iso) . '.svg') }}"
                                            alt="{{ $user->country->iso }} flag" class="w-6 h-6 mr-2 inline" />
                                        {{ $user->country->name }}
                                    @else
                                        <span class="text-gray-500">N/A</span>
                                    @endif

                                </p>
                            </td>
                            <td class="px-6 py-4 text-base font-medium">{{ $user->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-base font-medium">{{ $user->website ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-base font-medium">{{ $user->date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center px-6 py-4 text-md font-medium">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links('vendor.pagination.tailwind') }}
        @include('admin.users.layout.drawer-update')
        @include('admin.users.modal.img-remove')
    </section>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    let selectedUserId = null;

    document.addEventListener('DOMContentLoaded', function () {
        $('tr[data-href]').on('click', function () {
            const slug = $(this).data('href');

            $.ajax({
                url: `/admin/user/edit/${slug}`,
                type: "GET",
                success: function (data) {
                    selectedUserId = data.user.id;
                    $('#upload-pic, #upload-cover').val('');

                    $('#edit_user_id').val(data.user.id);
                    $('#edit_user_name').val(data.user.name);
                    $('#dob').val(data.user.birthday);
                    $('#edit_user_email').val(data.user.email);
                    $('#edit_user_gender').val(data.user.gender);

                    const matchedCountry = data.countries.find(c => c.id === data.user.country_id);
                    const dialCode = matchedCountry ? matchedCountry.dial : '';
                    $('#edit_user_phone').val('+' + dialCode + data.user.phone);
                    const stateSelect = $('#edit_user_state');
                    stateSelect.find('option[data-current-state="true"]').remove();
                    const savedState = data.user.state || '';
                    const hasProvince = stateSelect.find('option').toArray()
                        .some(option => option.value === savedState);
                    if (savedState && !hasProvince) {
                        const legacyState = new Option(`${savedState} (current)`, savedState, true, true);
                        legacyState.dataset.currentState = 'true';
                        stateSelect.append(legacyState);
                    }
                    stateSelect.val(savedState);
                    $('#edit_user_pic').attr('src', data.user.image);
                    $('#edit_user_cover').attr('src', data.user.cover);

                    // Role radio
                    $('#admin-radio').prop('checked', data.user.role == 1);
                    $('#regular-radio').prop('checked', data.user.role == 0);

                    // Populate country dropdown
                    const countrySelect = $('#edit_user_country_id');
                    countrySelect.empty().append('<option>Select country</option>');

                    data.countries.forEach(country => {
                        const isSelected = country.id === data.user.country_id ? 'selected' : '';
                        countrySelect.append(
                            `<option value="${country.id}" ${isSelected}>${country.name}</option>`
                        );
                    });

                    // Populate skills
                    const skillsContainer = $('#user-skills');
                    skillsContainer.empty();

                    if (data.skills && data.skills.length > 0) {
                        data.skills.forEach(skill => {
                            skillsContainer.append(`
                                <span id="skill-chip-${skill.id}" class="inline-flex items-center px-3 py-1 mr-2 mb-2 text-sm font-medium text-black bg-yellow-300 rounded-full">
                                    ${skill.name}
                                    <button type="button" class="ml-2 text-black hover:text-white focus:outline-none remove-skill" data-skill-id="${skill.id}">
                                        &times;
                                    </button>
                                </span>
                            `);
                        });
                    }

                    $('.btn-del-pic').attr('href', `/admin/users/delete/pic/${data.user.id}`);
                    $('.btn-del-user').attr('href', `/admin/users/delete/${data.user.id}`);
                },
                complete: function () {
                    const drawerElement = document.getElementById('drawer-update-user');
                    const drawer = new Drawer(drawerElement, {
                        placement: 'right',
                        bodyScrolling: true,
                        backdrop: true,
                    });
                    drawer.show();
                },
                error: function () {
                    alert('Unable to load user details.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Add skill
        $('#skill-input').on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                const skillName = $(this).val().trim();
                if (skillName !== '' && selectedUserId) {
                    $.ajax({
                        url: '/admin/user/skill/add',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: skillName,
                            user_id: selectedUserId
                        },
                        success: function (response) {
                            $('#user-skills').append(`
                                <span id="skill-chip-${response.id}" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center gap-1">
                                    ${response.name}
                                    <button class="remove-skill text-red-500 font-bold" data-skill-id="${response.id}">×</button>
                                </span>
                            `);
                            $('#skill-input').val('');
                        },
                        error: function (xhr) {
                            console.error('Add skill failed:', xhr.responseText);
                        }
                    });
                }
            }
        });

        // Remove skill
        $(document).on('click', '.remove-skill', function (e) {
            e.stopPropagation();
            const skillId = $(this).data('skill-id');

            if (!skillId || !selectedUserId) return;

            $.ajax({
                url: '/admin/user/skill/remove',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    skill_id: skillId,
                    user_id: selectedUserId
                },
                success: function () {
                    $(`#skill-chip-${skillId}`).remove();
                },
                error: function (xhr) {
                    console.error('Skill removal failed:', xhr.responseText);
                }
            });
        });
    });
</script>

<script>
  function previewUserCover(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function (event) { $('#edit_user_cover').attr('src', event.target.result); };
    reader.readAsDataURL(input.files[0]);
  }

  function openDeleteImageModal(button) {
    const userId = button.getAttribute('data-user-id');
    document.getElementById('confirmRemoveProfile').setAttribute('data-user-id', userId);
    document.getElementById('confirmRemoveCover').setAttribute('data-user-id', userId);
    document.getElementById('removeImageModal').classList.remove('hidden');
  }

  function closeDeleteImageModal() {
    document.getElementById('removeImageModal').classList.add('hidden');
  }

  $('#confirmRemoveProfile').on('click', function () {
    const userId = $(this).data('user-id');
    $.ajax({
      url: `/admin/user/delete/propic/${userId}`,
      type: 'DELETE',
      data: {
        _token: '{{ csrf_token() }}',
      },
      success: function () {
        $('#edit_user_pic').attr('src', '');
        closeDeleteImageModal();
      },
      error: function () {
        alert('Failed to remove profile picture.');
      }
    });
  });

  $('#confirmRemoveCover').on('click', function () {
    const userId = $(this).data('user-id');
    $.ajax({
      url: `/admin/user/delete/coverpic/${userId}`,
      type: 'DELETE',
      data: {
        _token: '{{ csrf_token() }}',
      },
      success: function () {
        $('#edit_user_cover').attr('src', '');
        closeDeleteImageModal();
      },
      error: function () {
        alert('Failed to remove cover picture.');
      }
    });
  });
</script>
<script>
// Confirmation for deleting a user from admin panel
$(document).on('click', '.btn-del-user', function(e) {
    e.preventDefault();
    const url = $(this).attr('href');
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        window.location.href = url;
    }
});
</script>

@endsection

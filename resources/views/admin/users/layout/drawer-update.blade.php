<!-- Edit User Drawer -->
<div id="drawer-update-user"
    class="fixed top-0 right-0 z-40 w-full h-screen max-w-xs p-4 overflow-y-auto transition-transform translate-x-full bg-white dark:bg-black"
    tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">

    <h5 id="drawer-label"
        class="inline-flex items-center mb-6 text-md font-semibold text-black dark:text-white uppercase">Edit Profile
    </h5>

    <button type="button" data-drawer-dismiss="drawer-update-user" aria-controls="drawer-update-user"
        class="absolute top-2.5 right-2.5 text-black dark:text-white hover:text-yellow-300 rounded-lg text-sm p-1.5">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
        </svg>
        <span class="sr-only">Close menu</span>
    </button>

    <form id="user-edit-form" action="{{ url('/admin/user/update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="edit_user_id" id="edit_user_id">
        <div>
        <!-- Cover + Profile Picture -->
       <div class="relative w-full h-40 rounded-lg overflow-hidden bg-gray-200 dark:bg-custom-dark mb-4">
       <!-- Cover image -->
       <img id="edit_user_cover" class="absolute inset-0 w-full h-full object-cover" src="#" alt="Cover Image">

      <!-- Profile image on the middle left -->
      <div class="absolute top-1/2 left-4 transform -translate-y-1/2">
        <img id="edit_user_pic" class="w-16 h-16 object-cover rounded-full border-4 border-white dark:border-black" src="#" alt="User Image">
       </div>
       </div>

            <div class="flex space-x-2 justify-center">
                <a type="button"
                    class="btn-user-history justify-center text-gray-900 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-full text-md px-2.5 py-2.5 text-center inline-flex items-center me-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" aria-hidden="true" viewBox="0 -960 960 960"
                        fill="currentColor">
                        <path
                            d="M480-80q-139-35-229.5-159.5T160-516v-244l320-120 320 120v244q0 85-29 163.5T688-214L560-342q-18 11-38.5 16.5T480-320q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 22-5.5 42.5T618-398l60 60q20-41 31-86t11-92v-189l-240-90-240 90v189q0 121 68 220t172 132q26-8 49.5-20.5T576-214l56 56q-33 27-71.5 47T480-80Zm0-320q33 0 56.5-23.5T560-480q0-33-23.5-56.5T480-560q-33 0-56.5 23.5T400-480q0 33 23.5 56.5T480-400Zm8-77Z" />
                    </svg>
                    <span class="sr-only">User History</span>
                </a>

                <a type="button"
                    class="text-white bg-[#050708] dark:bg-custom-dark hover:bg-[#050708]/80 font-medium rounded-full text-md px-5 py-2.5 text-center inline-flex items-center me-2 mb-2"
                    onclick="document.getElementById('upload-cover').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1 -ml-1" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Zm2 0v9l3.5-3.5 2.5 2.5 3.5-4L18 12V5H6Zm0 14h12v-4l-2.5-2.5-3.5 4-2.5-2.5L6 18v1Z"/>
                    </svg>
                    Cover
                </a>

                <a type="button"
                    class="text-white bg-[#050708] dark:bg-custom-dark hover:bg-[#050708]/80 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-medium rounded-full text-md px-5 text-center inline-flex items-center me-2 mb-2"
                    onclick="document.getElementById('upload-pic').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1 -ml-1" viewBox="0 -960 960 960"
                        fill="currentColor">
                        <path
                            d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                    Upload
                </a>

                <input id="upload-pic" type="file" name="image" class="hidden" />
                <input id="upload-cover" type="file" name="cover" accept="image/jpeg,image/png" class="hidden" onchange="previewUserCover(this)" />

                <a type="button"
   class="justify-center text-gray-900 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-full text-sm px-5 py-2.5 text-center inline-flex items-center me-2 mb-2"
   data-user-id="{{ $user->id }}"
   onclick="openDeleteImageModal(this)">
   <svg aria-hidden="true" class="w-5 h-5 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
       <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
   </svg>
   Remove
</a>
            </div>
        </div>

        <div class="space-y-4 mt-6">
            <div>
                <label for="edit_user_name" class="block mb-2 text-md font-medium text-black dark:text-white">Name</label>
                <input type="text" name="edit_user_name" id="edit_user_name"
                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5" required>
            </div>

            <div>
                <label for="edit_user_gender" class="block mb-2 text-md font-medium text-black dark:text-white">Gender</label>
                <select name="edit_user_gender" id="edit_user_gender"
                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5" required>
                    <option value="">Select gender</option>
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                </select>
            </div>

            <div>
                <label for="edit_user_dob" class="block mb-2 text-md font-medium text-black dark:text-white">Date of Birth</label>
                <input type="date" name="edit_user_dob" id="dob"
                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5" required>
            </div>

            <div>
                <label for="edit_user_email" class="block mb-2 text-md font-medium text-black dark:text-white">Email</label>
                <input type="email" name="edit_user_email" id="edit_user_email"
                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5" required>
            </div>

            <div>
                <label for="edit_user_country_id" class="block mb-2 text-md font-medium text-black dark:text-white">Country</label>
                <select id="edit_user_country_id" name="edit_user_country_id"
                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                    <option value="">Select country</option>
                    <!-- Populate dynamically -->
                </select>
            </div>

            <div>
                <label for="edit_user_state" class="block mb-2 text-md font-medium text-black dark:text-white">Province (PNG)</label>
                <select id="edit_user_state" name="edit_user_state"
                    class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-md rounded-full focus:ring-yellow-300 block w-full p-2.5">
                    <option value="">Select a province</option>
                    @foreach(config('png.provinces', []) as $province)
                        <option value="{{ $province }}">{{ $province }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="edit_user_phone" class="block mb-2 text-md font-medium text-black dark:text-white">Mobile Number</label>
                <input type="tel" name="edit_user_phone" id="edit_user_phone"
                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5" required>
            </div>

            <div>
    <label class="block mb-2 text-md font-medium text-black dark:text-white">Skills</label>
    <div id="user-skills" class="flex flex-wrap gap-2 mb-2">
        @foreach($user->skills as $skill)
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center gap-1">
                {{ $skill->name }}
                <button class="remove-skill text-red-500 font-bold" data-skill="{{ $skill->id }}">×</button>
            </span>
        @endforeach
    </div>

    <input type="text" id="skill-input" class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5" placeholder="Type a skill and press enter">
       </div>

            <div>
                <label class="block mb-2 text-md font-medium text-black dark:text-white">Account Type</label>
                <div class="flex space-x-4" id="edit_user_role">
                    <label class="flex items-center">
                        <input type="radio" value="1" name="user_role"
                            class="w-4 h-4 text-yellow-300 focus:ring-yellow-300">
                        <span class="ml-2 text-md text-black dark:text-white">Admin</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" value="2" name="user_role"
                            class="w-4 h-4 text-yellow-300 focus:ring-yellow-300">
                        <span class="ml-2 text-md text-black dark:text-white">Moderator</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" value="0" name="user_role"
                            class="w-4 h-4 text-yellow-300 focus:ring-yellow-300">
                        <span class="ml-2 text-md text-black dark:text-white">Regular</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block mb-2 text-md font-medium text-black dark:text-white">Account Status</label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="edit_user_ban_status" class="sr-only peer">
                    <div
                        class="relative w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-yellow-300 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full">
                    </div>
                    <span class="ml-3 text-md text-black dark:text-white">Active</span>
                </label>
                <input type="hidden" id="ban_status" name="ban_status" value="0">
            </div>
        </div>

        <div class="flex justify-center w-full pt-6 space-x-4">
            <button type="submit"
                    class="w-full justify-center text-white bg-[#050708] dark:bg-custom-dark hover:bg-[#050708]/80 focus:ring-4 focus:outline-none focus:ring-[#050708]/50 font-medium rounded-full text-md px-5  text-center inline-flex items-center me-2 mb-2">
                    Update
                </button>
            <a type="button"
                    class="w-full btn-del-user justify-center text-gray-900 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-full text-md px-5 py-2.5 text-center inline-flex items-center me-2 mb-2">
                    <svg aria-hidden="true" class="w-5 h-5 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Delete
                </a>
        </div>
    </form>
</div>

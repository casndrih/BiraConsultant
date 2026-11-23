<!-- Update Application Modal -->
<div id="viewApplicationModal" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
    <div class="relative w-full max-w-2xl max-h-full m-auto">

        <div class="relative bg-white rounded-lg shadow dark:bg-custom-dark">
            <button type="button"
                class="bc-modal-close absolute top-3 right-2.5 text-black dark:text-white bg-transparent rounded-lg text-md p-1.5 ml-auto inline-flex items-center"
                data-modal-hide="viewApplicationModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>

            <div class="flex px-6 py-3 rounded-t items-center">
                <h3 class="text-lg font-medium text-black dark:text-white">
                    Application Details
                </h3>
            </div>

            <div class="relative flex flex-col rounded-lg bg-gray-50 dark:bg-black">
                <form method="POST" action="{{ route('admin.application.update') }}">
                    @csrf
                    <div class="px-6 space-y-6 pb-6">
                        <!-- Hidden Inputs -->
                        <input type="hidden" id="view_application_id" name="edit_application_id">
                        <input type="hidden" id="view_application_userId" name="edit_application_userId">
                        <input type="hidden" id="view_application_jobId" name="edit_application_jobId">

                        <!-- Job Title -->
                        <div>
                            <label class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Job Title</label>
                            <input type="text" id="view_application_job_title" disabled
                                class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full block w-full p-2.5">
                        </div>

                       <!-- Submitted To (Company Name) -->
<div>
    <label class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Company</label>
    <input type="text" id="view_application_company" disabled
        class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full block w-full p-2.5"
        value="Loading...">
</div>

                        <!-- Status & Submitted Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">  
                            <div>
                                <label
                                    class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Date Submitted</label>
                                <div id="view_application_sub_date"
                                    class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-md rounded-full px-4 py-2.5">
                                    Loading...
                                </div>
                            </div>
                            
                            <div>
                                <label for="edit_application_status"
                                    class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Application
                                    Status</label>
                                <select id="edit_application_status" name="edit_application_status"
                                    class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                                    @foreach($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>

                        <hr class="border-gray-300 dark:border-gray-600" />

                        <!-- User Info -->
                        <div class="flex items-center gap-3">
                            <img id="view_user_image" src="{{ asset('/images/users/userpro.png') }}" alt="User Image"
                                class="w-16 h-16 rounded-full border-2 border-gray-200 dark:border-custom-dark object-cover" />
                            <div>
                                <h4 id="view_user_name" class="text-lg font-semibold text-black dark:text-white">Loading...</h4>
                                <p id="view_user_email" class="text-sm text-gray-500 dark:text-gray-400">Loading...</p>
                            </div>
                        </div>

                        <!-- Attachments Section -->
<div>
    <label class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Attachments</label>

    <!-- When both buttons are shown (grid layout) -->
    <div id="attachment_grid" class="hidden grid grid-cols-2 gap-4">
        <!-- Resume Button -->
        <a id="resume_button_grid"
           href="#"
           target="_blank"
           class="bc-modal-button--green-hover inline-flex items-center justify-center px-4 py-2 bg-black dark:bg-white text-white dark:text-black text-sm font-medium rounded-full">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm1 7V3.5L18.5 9H15zM8 15h8v2H8v-2zm0-4h8v2H8v-2z"/>
            </svg>
            CV/Resume
        </a>

        <!-- Cover Letter Button -->
        <a id="cover_letter_button"
           href="#"
           target="_blank"
           class="bc-modal-button--green-hover inline-flex items-center justify-center px-4 py-2.5 bg-black dark:bg-white text-white dark:text-black text-sm font-medium rounded-full">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm1 7V3.5L18.5 9H15zM8 15h8v2H8v-2zm0-4h8v2H8v-2z"/>
            </svg>
            Cover Letter
        </a>
    </div>

    <!-- When only Resume is shown (centered, medium width) -->
    <div id="resume_alone_wrapper" class="flex justify-center">
        <a id="resume_button_alone"
           href="#"
           target="_blank"
           class="bc-modal-button--green-hover inline-flex items-center justify-center px-4 py-2 mt-2 bg-black dark:bg-white text-white dark:text-black text-sm font-medium rounded-full w-auto">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm1 7V3.5L18.5 9H15zM8 15h8v2H8v-2zm0-4h8v2H8v-2z"/>
            </svg>
            Resume
        </a>
    </div>

    <!-- Optional: Cover Letter Placeholder -->
    <div id="cover_letter_placeholder"
         class="mt-2 text-sm text-center text-gray-500 dark:text-gray-400">
        No cover letter found.
    </div>
</div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 px-6 py-3 rounded-b bg-white dark:bg-custom-dark">
                        <button type="button" data-modal-hide="viewApplicationModal"
                            class="px-4 py-2 inline-flex items-center text-md font-medium text-black dark:text-black bg-gray-100 hover:bg-gray-200 rounded-full">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bc-modal-button--green-hover px-4 py-2 text-md font-medium text-white bg-black rounded-full">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

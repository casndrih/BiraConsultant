<!-- Edit Job modal -->
<div id="editJobModal" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
    <div class="relative w-full max-w-2xl max-h-full m-auto">
        
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-custom-dark">
            <button id="editModalCloseBtn" type="button"
                class="qj-modal-close absolute top-3 right-2.5 text-black dark:text-white bg-transparent rounded-lg text-md p-1.5 ml-auto inline-flex items-center"
                data-modal-hide="editJobModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>

            <!-- Modal header -->
            <div class="flex px-6 py-3 rounded-t items-center">
                <h3 class="inline-flex items-center text-lg font-medium text-black dark:text-white md:text-lg lg:text-xl">
                    Edit Job
                </h3>
            </div>

            <!-- Modal body -->
            <div class="relative flex flex-col rounded-lg bg-gray-50 dark:bg-black">
                <form action="{{ route('admin.job.update') }}" method="POST">
                  @csrf
                   <input type="hidden" name="edit_job_id" id="edit_job_id">

                   <div class="p-6 space-y-4">

                    <!-- Company Logo -->
                    <div class="flex justify-center mb-4">
                    <img id="edit_company_logo" src="" alt="Company Logo" class="w-28 h-28 object-contain" />
                    </div>

                    <div>
                        <label for="edit_title"
                            class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Job
                            Title</label>
                        <input type="text" id="edit_title" name="title" required
                            class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="edit_description"
                            class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea id="edit_description" name="description" rows="4"
                            class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-md focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_location"
                                class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Location</label>
                            <input type="text" id="edit_location" name="location"
                                class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                        </div>

                        <div>
                            <label for="edit_type"
                                class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Job
                                Type</label>
                            <select id="edit_type" name="job_type_id"
                                class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5"
                                required>
                            </select>
                        </div>

                        <div>
                            <label for="edit_salary"
                                class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Salary</label>
                            <input type="text" id="edit_salary" name="salary"
                                class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                        </div>

                        <div>
                            <label for="edit_experience"
                                class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Experience
                                Level</label>
                            <select id="edit_experience" name="experience_level_id"
                                class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5"
                                required>
                            </select>
                        </div>

                        <div>
                            <label for="edit_deadline"
                                class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                            <input type="date" id="edit_deadline" name="deadline"
                                class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                        </div>

                        <div>
                            <label class="block mb-2 text-md font-medium text-black dark:text-white">Job
                                Status</label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="edit_status_toggle" class="sr-only peer">
                                <div
                                    class="qj-modal-toggle relative w-9 h-5 bg-gray-200 rounded-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full">
                                </div>
                                <span class="ml-3 text-md text-black dark:text-white">Active</span>
                            </label>
                            <input type="hidden" id="edit_status" name="status" value="0">
                        </div>
                    </div>

                   </div>
                    <!-- Modal footer -->
                    <div class="flex flex-row p-3 gap-4 rounded-b-lg bg-white dark:bg-custom-dark">
                        <form id="deleteJobForm" method="POST" action="{{ route('admin.job.delete') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="delete_job_id" id="delete_job_id">
                    <button type="submit"
                    class="px-4 py-2 inline-flex items-center text-md font-medium text-black dark:text-black bg-gray-100 hover:bg-gray-200 rounded-full">
                    <svg aria-hidden="true" class="w-5 h-5 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Delete            
                </button>
                        </form>

                        <button type="submit"
                            class="qj-modal-button--green-hover px-4 py-2 text-md font-medium text-white bg-[#050708] dark:bg-black shadow-lg shadow-[#050708]/50 dark:shadow-black/50 rounded-full">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

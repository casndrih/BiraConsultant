<!-- Create Job modal -->
<div id="createJobModal" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
    <div class="relative w-full max-w-2xl max-h-full m-auto">

        <div class="relative bg-white rounded-lg shadow dark:bg-custom-dark">
            <button type="button"
                class="qj-modal-close absolute top-3 right-2.5 text-black dark:text-white bg-transparent rounded-lg text-md p-1.5 ml-auto inline-flex items-center"
                data-modal-hide="createJobModal">
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
                    Create New Job
                </h3>
            </div>

            <div class="relative flex flex-col rounded-lg bg-gray-50 dark:bg-black">
                <form action="{{ route('admin.job.create') }}" method="POST">
                    @csrf

                    <div class="p-6 space-y-4">

                        <div>
                            <label for="title" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Job Title</label>
                            <input type="text" id="title" name="title" required
                                class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                        </div>

                        <div>
                            <label for="description" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-md focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Company -->
                            <div>
                                <label for="company_id" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Company</label>
                                <select name="company_id" id="company_id" required
                                    class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <select name="category_id" id="category_id" required
                                    class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Location</label>
                                <input type="text" id="location" name="location"
                                    class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                            </div>

                            <!-- Job Type -->
                            <div>
                                <label for="job_type_id" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Job Type</label>
                                <select name="job_type_id" id="job_type_id" required
                                    class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                                    <option value="">Select Job Type</option>
                                    @foreach($jobTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Salary -->
                            <div>
                                <label for="salary" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Salary</label>
                                <input type="text" id="salary" name="salary"
                                    class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                            </div>

                            <!-- Experience Level -->
                            <div>
                                <label for="experience_level_id" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Experience Level</label>
                                <select name="experience_level_id" id="experience_level_id" required
                                    class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                                    <option value="">Select Level</option>
                                    @foreach($experienceLevels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Deadline -->
                            <div>
                                <label for="deadline" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                                <input type="date" id="deadline" name="deadline"
                                    class="bg-gray-100 dark:bg-custom-dark  text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5">
                            </div>

                            <!-- Job Status -->
                            <div>
                                <label class="inline-flex items-center cursor-pointer">
    <input type="checkbox" id="create_status_toggle" class="sr-only peer" checked>
    <div class="qj-modal-toggle relative w-9 h-5 bg-gray-200 rounded-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full"></div>
    <span id="create_status_label" class="ml-3 text-md text-black dark:text-white">Active</span>
</label>
<input type="hidden" id="create_status" name="status" value="1">
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="flex justify-end p-3 rounded-b-lg bg-white dark:bg-custom-dark">
                        <button type="submit"
                            class="qj-modal-button--green-hover px-4 py-2 text-md font-medium text-white bg-[#050708] dark:bg-black shadow-lg rounded-full">
                            Create Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggle = document.getElementById("create_status_toggle");
        const hiddenStatus = document.getElementById("create_status");
        const statusLabel = document.getElementById("create_status_label");

        function updateStatus() {
            if (toggle.checked) {
                hiddenStatus.value = "1";
                statusLabel.textContent = "Active";
            } else {
                hiddenStatus.value = "0";
                statusLabel.textContent = "Inactive";
            }
        }

        // Initialize on page load
        updateStatus();

        // Update on toggle
        toggle.addEventListener("change", updateStatus);
    });
</script>

<!-- Create Company modal -->
<div id="createCompanyModal" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
    <div class="relative w-full max-w-2xl max-h-full m-auto">

        <div class="relative bg-white rounded-lg shadow dark:bg-custom-dark">
            <button type="button"
                class="bc-modal-close absolute top-3 right-2.5 text-black dark:text-white bg-transparent rounded-lg text-md p-1.5 ml-auto inline-flex items-center"
                data-modal-hide="createCompanyModal">
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
                    Create New Company
                </h3>
            </div>

            <div class="relative flex flex-col rounded-lg bg-gray-50 dark:bg-black">
                <form action="{{ route('admin.company.create') }}" method="POST">
                    @csrf

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="company_name" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" id="company_name" name="name" required
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>
                            <div>
                                <label for="company_website" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Website</label>
                                <input type="url" id="company_website" name="website"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>

                            <div>
                                <label for="company_logo" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Logo URL</label>
                                <input type="text" id="company_logo" name="logo"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>
                            <div>
                                <label for="company_industry" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Industry</label>
                                <input type="text" id="company_industry" name="industry"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>

                            <div>
                                <label for="company_location" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Location</label>
                                <input type="text" id="company_location" name="location"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>
                            <div>
                                <label for="company_email" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" id="company_email" name="email"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>

                            <div>
                                <label for="company_phone" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                <input type="text" id="company_phone" name="phone"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>
                            <div>
                                <label for="company_founded" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Founded Year</label>
                                <input type="number" id="company_founded" name="founded_year" min="1800" max="{{ date('Y') }}"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>

                            <div class="md:col-span-2">
                                <label for="company_size" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Size</label>
                                <input type="text" id="company_size" name="size"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>

                            <div class="md:col-span-2">
                                <label for="company_description" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea id="company_description" name="description" rows="3"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-md focus:ring-yellow-300 focus: block w-full p-2.5"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="flex justify-end p-3 rounded-b-lg bg-white dark:bg-custom-dark">
                        <button type="button"
                            class="px-4 py-2 text-md font-medium text-black dark:text-black bg-gray-100 hover:bg-gray-200 rounded-full mr-2"
                            data-modal-hide="createCompanyModal">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bc-modal-button--green-hover px-4 py-2 text-md font-medium text-white bg-[#050708] dark:bg-black shadow-lg rounded-full">
                            Create Company
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

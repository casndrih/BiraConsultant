<!-- Edit Blog modal -->
<div id="editBlogModal" tabindex="-1" aria-hidden="true"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
    <div class="relative w-full max-w-3xl max-h-full m-auto">

        <div class="relative bg-white rounded-lg shadow dark:bg-custom-dark">
            <button type="button"
                class="qj-modal-close absolute top-3 right-2.5 text-black dark:text-white bg-transparent rounded-lg text-md p-1.5 ml-auto inline-flex items-center"
                data-modal-hide="editBlogModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>

            <div class="flex px-6 py-3 rounded-t items-center">
                <h3 class="inline-flex items-center text-lg font-medium text-black dark:text-white md:text-lg lg:text-xl">
                    Edit Blog Post
                </h3>
            </div>

            <div class="relative flex flex-col rounded-lg bg-gray-50 dark:bg-black">
                <form action="{{ route('admin.blog.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="edit_blog_id" id="edit_blog_id">

                    <div class="p-6 space-y-4">
                        <!-- Title & Slug -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label for="edit_blog_title" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input type="text" id="edit_blog_title" name="title" required
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>
                            <div>
                                <label for="edit_blog_slug" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Slug</label>
                                <input type="text" id="edit_blog_slug" name="slug"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div>
                            <label for="edit_blog_excerpt" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Excerpt</label>
                            <textarea id="edit_blog_excerpt" name="excerpt" rows="2" maxlength="255"
                                class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-md focus:ring-yellow-300 focus: block w-full p-2.5"></textarea>
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="edit_blog_content" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Content</label>
                            <textarea id="edit_blog_content" name="content" rows="8"
                                class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-md focus:ring-yellow-300 focus: block w-full p-2.5"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Category -->
                            <div>
                                <label for="edit_blog_category" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <select name="category_id" id="edit_blog_category"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                                    <option value="">Select Category</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="edit_blog_status" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="edit_blog_status" required
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Published At -->
                            <div>
                                <label for="edit_blog_published_at" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Publish Date</label>
                                <input type="datetime-local" id="edit_blog_published_at" name="published_at"
                                    class="bg-gray-100 dark:bg-custom-dark dark: text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus: block w-full p-2.5">
                            </div>

                            <!-- Featured Image -->
                            <div>
                                <label for="edit_blog_featured_image" class="block mb-2 text-md font-medium text-gray-700 dark:text-gray-300">Featured Image</label>
                                <input type="file" id="edit_blog_featured_image" name="featured_image" accept="image/jpeg,image/png,image/jpg"
                                    class="bg-gray-100 dark:bg-custom-dark text-black dark:text-white text-md rounded-full focus:ring-yellow-300 focus:border-yellow-300 block w-full p-2.5 file:mr-4 file:py-0 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold qj-modal-file-control">
                                <div id="edit_blog_image_preview_container" class="mt-2">
                                    <img id="edit_blog_featured_image_preview" src="" alt="Current featured image" class="hidden w-32 h-20 object-cover rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="flex justify-end p-3 rounded-b-lg bg-white dark:bg-custom-dark">
                        <button type="button"
                            class="px-4 py-2 text-md font-medium text-black dark:text-black bg-gray-100 hover:bg-gray-200 rounded-full mr-2"
                            data-modal-hide="editBlogModal">
                            Cancel
                        </button>
                        <button type="submit"
                            class="qj-modal-button--green-hover px-4 py-2 text-md font-medium text-white bg-[#050708] dark:bg-black shadow-lg rounded-full">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

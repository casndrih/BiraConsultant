@extends('admin.app')

@section('content')
<section class="bg-gray-100 dark:bg-black py-2">
    <div class="relative overflow-x-auto mx-4 shadow-md rounded-t-lg">
        <div class="flex items-center justify-between py-4 bg-white dark:bg-custom-dark">
            <div class="qj-admin-search relative flex items-center">
                <form class="qj-admin-search__form relative" action="{{ route('admin.blog.search') }}" method="GET">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" id="query-blog" name="query-blog"
                           value="{{ request()->query('query-blog') }}"
                           class="block w-full p-2 text-base text-gray-900 rounded-full bg-gray-100 dark:bg-black focus:ring-yellow-300 focus:border-yellow-300"
                           placeholder="Search Blog">
                </form>
            </div>

            <div class="flex items-center">
                <button type="button" data-modal-target="createBlogModal"
                    data-modal-show="createBlogModal"
                    class="text-white bg-black font-medium rounded-full text-md px-5 py-2.5 ml-4 mr-4">
                    New Post
                </button>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto mx-4 shadow-md rounded-b-lg">
        <table class="w-full text-sm text-left text-black qj-admin-data-table qj-admin-data-table--blogs">
            <thead class="text-md text-black dark:text-white font-medium uppercase bg-white dark:bg-custom-dark">
            <tr>
                <th class="px-6 py-4">Title</th>
                <th class="px-6 py-4">Author</th>
                <th class="px-6 py-4">Category</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Published</th>
                <th class="px-6 py-4">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($blogs as $blog)
                <tr class="bg-gray-50 dark:bg-black hover:bg-gray-100 dark:hover:bg-custom-dark"
                    data-id="{{ $blog->id }}">
                    <td class="px-6 py-4 text-base font-medium text-black dark:text-white">
                        {{ Str::limit($blog->title, 40) }}
                    </td>
                    <td class="px-6 py-4 text-base text-black dark:text-white">{{ $blog->author->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-base text-black dark:text-white">{{ $blog->category->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                'published' => 'bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300',
                                'archived' => 'bg-yellow-200 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                            ];
                            $statusColor = $statusColors[$blog->status] ?? 'bg-gray-200 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                            {{ ucfirst($blog->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-base text-black dark:text-white">
                        {{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d') : 'N/A' }}
                    </td>
                    <td class="px-4 py-4">
                        <div class="qj-admin-table-actions">
                            <button type="button" onclick="openEditBlogModal('{{ $blog->slug }}')" class="qj-admin-action-btn text-sm font-medium text-black bg-yellow-300 hover:bg-yellow-400 rounded-full">Edit</button>
                            <button type="button" onclick="confirmDeleteBlog({{ $blog->id }}, @js($blog->title))" class="qj-admin-action-btn text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full">Delete</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-lg text-gray-700">No blog posts found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mx-4 my-4">
        {{ $blogs->links('vendor.pagination.tailwind') }}
    </div>

    @include('admin.blogs.modal.blog-create-modal')
    @include('admin.blogs.modal.blog-edit-modal')

    {{-- Delete confirmation modal --}}
    <div id="deleteBlogModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black/50">
        <div class="relative w-full max-w-md max-h-full m-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-custom-dark">
                <button type="button"
                    class="absolute top-3 right-2.5 text-black dark:text-white bg-transparent hover:text-yellow-300 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-hide="deleteBlogModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="p-6 text-center">
                    <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-black dark:text-white">
                        Are you sure you want to delete <span id="deleteBlogName" class="font-bold"></span>?
                    </h3>
                    <form id="deleteBlogForm" method="GET">
                        <button type="submit"
                            class="px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-full mr-2">
                            Yes, delete it
                        </button>
                        <button type="button"
                            class="px-5 py-2 text-sm font-medium text-black bg-gray-100 hover:bg-gray-200 rounded-full"
                            data-modal-hide="deleteBlogModal">
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
    $(document).on('click', '[data-modal-show="createBlogModal"]', function () {
        const modal = document.getElementById('createBlogModal');
        const modalInstance = new Modal(modal);
        modalInstance.show();
    });

    $(document).on('click', '[data-modal-hide="createBlogModal"]', function () {
        const modal = document.getElementById('createBlogModal');
        const modalInstance = Modal.getInstance(modal);
        if (modalInstance) modalInstance.hide();
    });

    // Auto-generate slug from title
    $('#blog_title').on('input', function () {
        const slug = $(this).val()
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        $('#blog_slug').val(slug);
    });

    // Edit modal
    window.openEditBlogModal = function (blogSlug) {
        $.ajax({
            url: `/admin/blog/edit/${blogSlug}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                populateEditBlogModal(response);
            },
            error: function () {
                alert('Unable to fetch blog data. Please try again.');
            }
        });
    };

    function populateEditBlogModal(data) {
        $('#edit_blog_id').val(data.id);
        $('#edit_blog_title').val(data.title ?? '');
        $('#edit_blog_slug').val(data.slug ?? '');
        $('#edit_blog_excerpt').val(data.excerpt ?? '');
        $('#edit_blog_content').val(data.content ?? '');
        $('#edit_blog_status').val(data.status ?? 'draft');
        $('#edit_blog_published_at').val(data.published_at ?? '');
        $('#edit_blog_featured_image_preview').attr('src', data.featured_image ?? '');

        // Populate category dropdown
        const categorySelect = $('#edit_blog_category');
        categorySelect.empty();
        categorySelect.append('<option value="">Select Category</option>');
        if (typeof blogCategories !== 'undefined') {
            blogCategories.forEach(cat => {
                const selected = (cat.id === data.category_id) ? 'selected' : '';
                categorySelect.append(`<option value="${cat.id}" ${selected}>${cat.name}</option>`);
            });
        }

        const modal = document.getElementById('editBlogModal');
        const modalInstance = new Modal(modal);
        modalInstance.show();
    }

    $(document).on('click', '[data-modal-hide="editBlogModal"]', function () {
        const modal = document.getElementById('editBlogModal');
        const modalInstance = Modal.getInstance(modal);
        if (modalInstance) modalInstance.hide();
    });

    // Delete confirmation
    window.confirmDeleteBlog = function (blogSlug, blogTitle) {
        $('#deleteBlogName').text(blogTitle);
        $('#deleteBlogForm').attr('action', `/admin/blog/delete/${blogSlug}`);

        const modal = document.getElementById('deleteBlogModal');
        const modalInstance = new Modal(modal);
        modalInstance.show();
    };

    $(document).on('click', '[data-modal-hide="deleteBlogModal"]', function () {
        const modal = document.getElementById('deleteBlogModal');
        const modalInstance = Modal.getInstance(modal);
        if (modalInstance) modalInstance.hide();
    });
</script>
@endsection

<div class="bg-white dark:bg-custom-dark rounded-lg shadow-sm mb-6">
    <div class="h-6 skeleton-box rounded w-32 mb-4"></div>
    <div class="flex items-center justify-between text-sm">
        <div class="h-4 skeleton-box rounded w-24"></div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Company Profile Skeleton -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-start gap-6 mb-6">
                    <div class="w-24 h-24 rounded-lg skeleton-box flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="h-8 skeleton-box rounded w-3/4 mb-3"></div>
                        <div class="h-4 skeleton-box rounded w-1/2 mb-2"></div>
                        <div class="h-4 skeleton-box rounded w-2/3 mb-2"></div>
                        <div class="h-4 skeleton-box rounded w-5/6 mb-2"></div>
                    </div>
                </div>
                <div class="mb-6">
                    <div class="h-6 skeleton-box rounded w-1/3 mb-4"></div>
                    <div class="h-4 skeleton-box rounded w-full mb-2"></div>
                    <div class="h-4 skeleton-box rounded w-full mb-2"></div>
                    <div class="h-4 skeleton-box rounded w-5/6 mb-2"></div>
                    <div class="h-4 skeleton-box rounded w-4/6 mb-2"></div>
                    <div class="h-4 skeleton-box rounded w-3/4"></div>
                </div>
                <div class="border-t border-gray-200 dark:border-black pt-6">
                    <div class="h-5 skeleton-box rounded w-1/4 mb-4"></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="h-4 skeleton-box rounded w-full mb-2"></div>
                        <div class="h-4 skeleton-box rounded w-full mb-2"></div>
                        <div class="h-4 skeleton-box rounded w-full mb-2"></div>
                        <div class="h-4 skeleton-box rounded w-full mb-2"></div>
                        <div class="h-4 skeleton-box rounded w-3/4"></div>
                    </div>
                </div>
            </div>

            <!-- Jobs Skeleton -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6">
                <div class="h-6 skeleton-box rounded w-1/2 mb-6"></div>
                <div class="space-y-4">
                    @for($i = 0; $i < 4; $i++)
                    <div class="border dark:border-black rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="h-5 skeleton-box rounded w-3/4 mb-2"></div>
                                <div class="h-4 skeleton-box rounded w-1/2 mb-2"></div>
                                <div class="h-4 skeleton-box rounded w-2/3 mb-2"></div>
                                <div class="h-3 skeleton-box rounded w-4/6 mb-2"></div>
                            </div>
                            <div class="h-5 skeleton-box rounded w-16"></div>
                        </div>
                    </div>
                    @endfor
                </div>
                <div class="flex justify-center mt-6">
                    <div class="h-4 skeleton-box rounded w-32"></div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Quick Info Skeleton -->
            <div class="bg-white dark:bg-custom-dark rounded-lg shadow-md p-6 mb-6 sticky top-4">
                <div class="h-5 skeleton-box rounded w-1/2 mb-4"></div>
                <div class="space-y-4">
                    <div>
                        <div class="h-3 skeleton-box rounded w-1/3 mb-1"></div>
                        <div class="h-4 skeleton-box rounded w-2/3"></div>
                    </div>
                    <div>
                        <div class="h-3 skeleton-box rounded w-1/3 mb-1"></div>
                        <div class="h-4 skeleton-box rounded w-1/2"></div>
                    </div>
                    <div>
                        <div class="h-3 skeleton-box rounded w-1/3 mb-1"></div>
                        <div class="h-4 skeleton-box rounded w-2/5"></div>
                    </div>
                    <div>
                        <div class="h-3 skeleton-box rounded w-1/3 mb-1"></div>
                        <div class="h-4 skeleton-box rounded w-1/4"></div>
                    </div>
                    <div>
                        <div class="h-3 skeleton-box rounded w-1/3 mb-1"></div>
                        <div class="h-4 skeleton-box rounded w-1/4"></div>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-black">
                    <div class="h-9 skeleton-box rounded w-full"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@forelse($featuredJobs as $job)
    @include('components.job-card', ['job' => $job])
@empty
    <div class="col-span-full text-center py-8">
        <p class="text-gray-900 dark:text-white">No featured jobs yet.</p>
    </div>
@endforelse
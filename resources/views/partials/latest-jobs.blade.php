@forelse($latestJobs as $job)
    @include('components.job-card', ['job' => $job])
@empty
    <div class="col-span-full text-center py-12">
        <p class="text-gray-500 dark:text-gray-400">No jobs match the selected filters.</p>
    </div>
@endforelse
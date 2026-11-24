@extends('admin.app')

@section('content')
<section class="qj-dashboard-page">
    <div class="qj-dashboard-hero">
        <div class="qj-dashboard-hero__inner">
            <span class="qj-content-hero__eyebrow">BiraConsultant administration</span>
            <h1 class="qj-content-hero__title">Platform overview</h1>
            <p class="qj-content-hero__subtitle">A live snapshot of jobs, applications, employers, and members.</p>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stat Cards -->
        <div class="qj-dashboard-stats-grid my-7">
            <div class="bg-white dark:bg-custom-dark shadow-sm rounded-lg p-4 text-center">
                <p class="text-gray-600 dark:text-gray-300">Active Jobs</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $totalJobs }}</p>
            </div>
            <div class="bg-white dark:bg-custom-dark shadow-sm rounded-lg p-4 text-center">
                <p class="text-gray-600 dark:text-gray-300">Total Applications</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $totalApplications }}</p>
            </div>
            <div class="bg-white dark:bg-custom-dark shadow-sm rounded-lg p-4 text-center">
                <p class="text-gray-600 dark:text-gray-300">Pending Applications</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $pendingApplications }}</p>
            </div>
            <div class="bg-white dark:bg-custom-dark shadow-sm rounded-lg p-4 text-center">
                <p class="text-gray-600 dark:text-gray-300">Total Companies</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $totalCompanies }}</p>
            </div>
            <div class="bg-white dark:bg-custom-dark shadow-sm rounded-lg p-4 text-center">
                <p class="text-gray-600 dark:text-gray-300">Total Blogs</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $totalBlogs }}</p>
            </div>
            <div class="bg-white dark:bg-custom-dark shadow-sm rounded-lg p-4 text-center">
                <p class="text-gray-600 dark:text-gray-300">Applications Today</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $todayApplications }}</p>
            </div>
            <div class="bg-white dark:bg-custom-dark shadow-sm rounded-lg p-4 text-center">
                <p class="text-gray-600 dark:text-gray-300">Users Joined Today</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $todayUsers }}</p>
            </div>
        </div>

        <!-- Jobs, Applications & Users Chart -->
        <div class="qj-dashboard-panel qj-dashboard-monthly-panel max-w-full w-full p-6 mb-10">
            <div class="mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Monthly Overview</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Comparison of jobs, applications, and user registrations</p>
            </div>
            <div id="dashboardChart" class="w-full"></div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const options = {
        chart: {
            type: 'line',
            height: 200,
            toolbar: { show: false }
        },
        series: [
            {
                name: 'Users',
                data: @json($usersData)
            },
            {
                name: 'Applications',
                data: @json($appsData)
            },
            {
                name: 'Companies',
                data: @json($companiesData)
            },
            {
                name: 'Blogs',
                data: @json($blogsData)
            },
        ],
        xaxis: {
            categories: @json($monthlyLabels),
            title: { text: 'Month' }
        },
        yaxis: {
            title: { text: 'Count' }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        colors: ['#78ad68', '#b9e49e', '#3e7143', '#c9e9b7'],
        legend: {
            position: 'top'
        },
        tooltip: {
            shared: true,
            intersect: false
        }
    };

    new ApexCharts(document.querySelector("#dashboardChart"), options).render();
});
</script>
@endsection

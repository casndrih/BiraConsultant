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
    <div class="qj-admin-dashboard-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stat Cards -->
        <div class="qj-dashboard-stats-grid">
            <div class="qj-dashboard-stat text-center">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Active Jobs</p>
                <p class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $totalJobs }}</p>
            </div>
            <div class="qj-dashboard-stat text-center">
                <p class="text-gray-600 dark:text-gray-300">Total Applications</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $totalApplications }}</p>
            </div>
            <div class="qj-dashboard-stat text-center">
                <p class="text-gray-600 dark:text-gray-300">Pending Applications</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $pendingApplications }}</p>
            </div>
            <div class="qj-dashboard-stat text-center">
                <p class="text-gray-600 dark:text-gray-300">Total Companies</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $totalCompanies }}</p>
            </div>
            <div class="qj-dashboard-stat text-center">
                <p class="text-gray-600 dark:text-gray-300">Total Blogs</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $totalBlogs }}</p>
            </div>
            <div class="qj-dashboard-stat text-center">
                <p class="text-gray-600 dark:text-gray-300">Applications Today</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $todayApplications }}</p>
            </div>
            <div class="qj-dashboard-stat text-center">
                <p class="text-gray-600 dark:text-gray-300">Users Joined Today</p>
                <p class="text-3xl font-medium text-gray-800 dark:text-white">{{ $todayUsers }}</p>
            </div>
        </div>

        <!-- Jobs, Applications & Users Chart -->
        <div class="qj-dashboard-panel qj-dashboard-monthly-panel max-w-full w-full p-6 mb-10">
            <div class="mb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Monthly Overview</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Monthly totals for users, applications, companies, and blogs</p>
            </div>
            <div id="dashboardChart" class="w-full min-w-0" role="img" aria-label="Monthly totals for users, applications, companies, and blogs"></div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const isDarkMode = () => document.documentElement.classList.contains('dark') ||
        document.body.classList.contains('dark');

    const options = {
        chart: {
            type: 'line',
            height: 320,
            toolbar: { show: false },
            parentHeightOffset: 0,
            redrawOnParentResize: true,
            redrawOnWindowResize: true,
            background: 'transparent',
            foreColor: isDarkMode() ? '#cbd5e1' : '#475569',
            theme: { mode: isDarkMode() ? 'dark' : 'light' },
            grid: { borderColor: isDarkMode() ? '#334155' : '#e2e8f0' }
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
            labels: { style: { colors: isDarkMode() ? '#a8b3c4' : '#64748b' } },
            axisBorder: { color: isDarkMode() ? '#334155' : '#e2e8f0' },
            axisTicks: { color: isDarkMode() ? '#334155' : '#e2e8f0' },
            categories: @json($monthlyLabels),
            title: { text: 'Month' }
        },
        yaxis: {
            labels: { style: { colors: isDarkMode() ? '#a8b3c4' : '#64748b' } },
            title: { text: 'Count' }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        colors: ['#78ad68', '#b9e49e', '#3e7143', '#c9e9b7'],
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            fontSize: '12px',
            labels: { colors: isDarkMode() ? '#cbd5e1' : '#475569' }
        },
        responsive: [{
            breakpoint: 640,
            options: {
                chart: { height: 280 },
                legend: { position: 'bottom', horizontalAlign: 'left' },
                xaxis: { labels: { rotate: -45, trim: true } }
            }
        }],
        tooltip: {
            theme: isDarkMode() ? 'dark' : 'light',
            shared: true,
            intersect: false
        }
    };

    const chart = new ApexCharts(document.querySelector("#dashboardChart"), options);
    chart.render();

    // Keep the chart readable when the user switches theme without reloading.
    const themeObserver = new MutationObserver(() => {
        const dark = isDarkMode();
        chart.updateOptions({
            theme: { mode: dark ? 'dark' : 'light' },
            chart: { foreColor: dark ? '#cbd5e1' : '#475569' },
            grid: { borderColor: dark ? '#334155' : '#e2e8f0' },
            xaxis: {
                labels: { style: { colors: dark ? '#a8b3c4' : '#64748b' } },
                axisBorder: { color: dark ? '#334155' : '#e2e8f0' },
                axisTicks: { color: dark ? '#334155' : '#e2e8f0' }
            },
            yaxis: { labels: { style: { colors: dark ? '#a8b3c4' : '#64748b' } } },
            legend: { labels: { colors: dark ? '#cbd5e1' : '#475569' } },
            tooltip: { theme: dark ? 'dark' : 'light' }
        });
    });

    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    themeObserver.observe(document.body, { attributes: true, attributeFilter: ['class'] });
});
</script>
@endsection

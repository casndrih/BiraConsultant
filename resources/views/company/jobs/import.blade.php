@extends('app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-black">
    <div class="bc-dashboard-hero">
        <div class="container mx-auto px-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">Bulk upload jobs</h1>
                <p class="text-sm opacity-75 mt-1">{{ $company->name }} · Upload a spreadsheet with up to 500 job rows.</p>
            </div>
            <a href="{{ route('company.dashboard') }}" class="px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-sm font-semibold">Back to dashboard</a>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-4xl space-y-6">
        @if($errors->any())
            <div class="rounded-xl bg-red-50 dark:bg-red-950/40 p-4 text-sm text-red-800 dark:text-red-200">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if(session('import_summary'))
            @php($summary = session('import_summary'))
            <div class="rounded-xl bg-green-50 dark:bg-green-950/30 p-4 text-sm text-green-900 dark:text-green-200">
                Imported <strong>{{ $summary['imported'] }}</strong> jobs. Skipped <strong>{{ $summary['skipped'] }}</strong> invalid rows.
                @if($summary['imported'] > 0) Imported jobs are pending review before they appear in the app. @endif
            </div>
        @endif

        @if(session('import_errors'))
            <div class="rounded-xl bg-white dark:bg-custom-dark p-5 shadow-sm">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-3">Rows to correct</h2>
                @if(session('import_errors_truncated'))
                    <p class="text-xs text-gray-500 mb-3">Showing the first 50 row errors.</p>
                @endif
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach(session('import_errors') as $rowError)
                        <div class="border-b border-gray-100 dark:border-gray-700 pb-3 last:border-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Spreadsheet row {{ $rowError['row'] }}</p>
                            <ul class="list-disc list-inside text-xs text-red-700 dark:text-red-300 mt-1">
                                @foreach($rowError['messages'] as $message)<li>{{ $message }}</li>@endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <section class="rounded-2xl bg-white dark:bg-custom-dark p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Choose a spreadsheet</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload an Excel workbook (.xlsx) or a CSV exported from Excel.</p>
                </div>
                <a href="{{ route('company.jobs.import.template') }}" class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-900 dark:bg-green-900/40 dark:text-green-200 text-sm font-semibold hover:bg-green-200 dark:hover:bg-green-900/60">Download CSV template</a>
            </div>

            <form action="{{ route('company.jobs.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <label for="jobs_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Spreadsheet file</label>
                <input id="jobs_file" name="jobs_file" type="file" accept=".xlsx,.csv" required
                    class="block w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:rounded-full file:border-0 file:bg-gray-100 dark:file:bg-gray-700 file:px-4 file:py-2 file:font-semibold file:text-gray-800 dark:file:text-white hover:file:bg-gray-200 dark:hover:file:bg-gray-600">
                <p class="text-xs text-gray-500 dark:text-gray-400">Maximum file size: 10 MB. The first non-empty row must contain the column names.</p>
                <button type="submit" class="px-6 py-2.5 rounded-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold">Upload jobs</button>
            </form>
        </section>

        <section class="rounded-2xl bg-white dark:bg-custom-dark p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Spreadsheet columns</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4">Use these column names in the header row. Category, job type and experience level accept a name from the lists below or its numeric ID.</p>
            <div class="grid sm:grid-cols-2 gap-5 text-sm">
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Required</h3>
                    <ul class="space-y-1 text-gray-600 dark:text-gray-400">
                        <li><code>title</code> — job title</li>
                        <li><code>description</code> — role description</li>
                        <li><code>category</code> — BiraConsultant category</li>
                        <li><code>job_type</code> — employment type</li>
                        <li><code>experience_level</code> — experience level</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Optional</h3>
                    <ul class="space-y-1 text-gray-600 dark:text-gray-400">
                        <li><code>location</code>, <code>salary</code></li>
                        <li><code>deadline</code> — date such as YYYY-MM-DD</li>
                        <li><code>duration</code> — 1 to 365 days; defaults to 30</li>
                        <li><code>requirements</code>, <code>how_to_apply</code></li>
                    </ul>
                </div>
            </div>
            <div class="mt-5 grid md:grid-cols-3 gap-4 text-xs text-gray-600 dark:text-gray-400">
                <div><span class="font-semibold text-gray-800 dark:text-gray-200">Categories:</span> {{ $categories->implode(', ') }}</div>
                <div><span class="font-semibold text-gray-800 dark:text-gray-200">Job types:</span> {{ $jobTypes->implode(', ') }}</div>
                <div><span class="font-semibold text-gray-800 dark:text-gray-200">Experience:</span> {{ $experienceLevels->implode(', ') }}</div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-5">Each valid row is added to your company and sent for admin review. Invalid rows are skipped and listed with their spreadsheet row numbers.</p>
        </section>
    </div>
</div>
@endsection

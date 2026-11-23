<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QJobsUser;
use App\Models\QJobsApplication;
use App\Models\QJobsCompany;
use App\Models\QJobsJob;
use App\Models\QJobsBlog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->format('M');
        });

        $usersPerMonth = QJobsUser::select(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', now()->year)
            ->where('role', 0)
            ->groupBy(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER)'))
            ->pluck('count', 'month');

        $applicationsPerMonth = QJobsApplication::select(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER)'))
            ->pluck('count', 'month');

        $jobsPerMonth = QJobsJob::select(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER)'))
            ->pluck('count', 'month');

        $companiesPerMonth = QJobsCompany::select(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER)'))
            ->pluck('count', 'month');

        $blogsPerMonth = QJobsBlog::select(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('CAST(strftime(\'%m\', created_at) AS INTEGER)'))
            ->pluck('count', 'month');

        return view('admin.dashboard.index', [
            // Monthly Chart Data
            'monthlyLabels' => $months,
            'usersData'     => $months->map(fn($m, $i) => $usersPerMonth[$i + 1] ?? 0),
            'appsData'      => $months->map(fn($m, $i) => $applicationsPerMonth[$i + 1] ?? 0),
            'jobsData'      => $months->map(fn($m, $i) => $jobsPerMonth[$i + 1] ?? 0),
            'companiesData' => $months->map(fn($m, $i) => $companiesPerMonth[$i + 1] ?? 0),
            'blogsData'     => $months->map(fn($m, $i) => $blogsPerMonth[$i + 1] ?? 0),

            // Overall Stats
            'totalUsers'             => QJobsUser::where('role', 0)->count(),
            'totalApplications'      => QJobsApplication::count(),
            'pendingApplications'    => QJobsApplication::where('status_id', 1)->count(),
            'successfulApplications' => QJobsApplication::where('status_id', 2)->count(),
            'unsuccessfulApplications'=> QJobsApplication::where('status_id', 5)->count(),
            'totalCompanies'         => QJobsCompany::count(),
            'totalJobs'              => QJobsJob::count(),
            'totalBlogs'             => QJobsBlog::count(),
            // Today Stats
            'todayApplications'      => QJobsApplication::whereDate('created_at', now()->toDateString())->count(),
            'todayUsers'            => QJobsUser::where('role', 0)->whereDate('created_at', now()->toDateString())->count(),

            // Latest Entries
            'latestUsers' => QJobsUser::where('role', 0)->latest()->take(5)->get(),
            'latestApplications' => QJobsApplication::with(['user', 'job'])->latest()->take(5)->get(),
            'latestCompanies' => QJobsCompany::latest()->take(5)->get(),
            'latestJobs' => QJobsJob::with(['company', 'category'])->withCount('applications')->where('status', 1)->latest()->paginate(5),
            'latestBlogs' => QJobsBlog::with('category')->latest()->take(5)->get(),
        ]);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantUser;
use App\Models\BiraConsultantApplication;
use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantBlog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->format('M');
        });

        // Use SQL date functions supported by both the production MySQL database
        // and the SQLite database used by the automated test suite.
        $monthExpression = DB::connection()->getDriverName() === 'sqlite'
            ? "CAST(strftime('%m', created_at) AS INTEGER)"
            : "MONTH(created_at)";

        $usersPerMonth = BiraConsultantUser::selectRaw("$monthExpression as month, COUNT(*) as count")
            ->whereYear('created_at', now()->year)
            ->where('role', 0)
            ->groupBy('month')
            ->pluck('count', 'month');

        $applicationsPerMonth = BiraConsultantApplication::selectRaw("$monthExpression as month, COUNT(*) as count")
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $jobsPerMonth = BiraConsultantJob::selectRaw("$monthExpression as month, COUNT(*) as count")
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $companiesPerMonth = BiraConsultantCompany::selectRaw("$monthExpression as month, COUNT(*) as count")
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $blogsPerMonth = BiraConsultantBlog::selectRaw("$monthExpression as month, COUNT(*) as count")
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
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
            'totalUsers'             => BiraConsultantUser::where('role', 0)->count(),
            'totalApplications'      => BiraConsultantApplication::count(),
            'pendingApplications'    => BiraConsultantApplication::where('status_id', 1)->count(),
            'successfulApplications' => BiraConsultantApplication::where('status_id', 2)->count(),
            'unsuccessfulApplications'=> BiraConsultantApplication::where('status_id', 5)->count(),
            'totalCompanies'         => BiraConsultantCompany::count(),
            'totalJobs'              => BiraConsultantJob::count(),
            'totalBlogs'             => BiraConsultantBlog::count(),
            // Today Stats
            'todayApplications'      => BiraConsultantApplication::whereDate('created_at', now()->toDateString())->count(),
            'todayUsers'            => BiraConsultantUser::where('role', 0)->whereDate('created_at', now()->toDateString())->count(),

            // Latest Entries
            'latestUsers' => BiraConsultantUser::where('role', 0)->latest()->take(5)->get(),
            'latestApplications' => BiraConsultantApplication::with(['user', 'job'])->latest()->take(5)->get(),
            'latestCompanies' => BiraConsultantCompany::latest()->take(5)->get(),
            'latestJobs' => BiraConsultantJob::with(['company', 'category'])->withCount('applications')->where('status', 1)->latest()->paginate(5),
            'latestBlogs' => BiraConsultantBlog::with('category')->latest()->take(5)->get(),
        ]);
    }
}
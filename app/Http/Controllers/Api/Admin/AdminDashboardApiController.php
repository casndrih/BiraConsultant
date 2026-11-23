<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\QJobsJob;
use App\Models\QJobsCompany;
use App\Models\QJobsUser;
use App\Models\QJobsApplication;

class AdminDashboardApiController extends Controller
{
    public function index()
    {
        $stats = [
            'total_jobs' => QJobsJob::count(),
            'pending_jobs' => QJobsJob::where('approval_status', 'pending')->count(),
            'approved_jobs' => QJobsJob::where('approval_status', 'approved')->count(),
            'total_companies' => QJobsCompany::count(),
            'pending_companies' => QJobsCompany::where('verification_status', 0)->count(),
            'verified_companies' => QJobsCompany::where('verification_status', 1)->count(),
            'total_users' => QJobsUser::where('role', 0)->count(),
            'total_applications' => QJobsApplication::count(),
            'pending_applications' => QJobsApplication::where('status_id', 1)->count(),
            'pending_identity' => QJobsUser::whereNotNull('nid_front_path')->where('identity_verified', 0)->count(),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantUser;
use App\Models\BiraConsultantApplication;

class AdminDashboardApiController extends Controller
{
    public function index()
    {
        $stats = [
            'total_jobs' => BiraConsultantJob::count(),
            'pending_jobs' => BiraConsultantJob::where('approval_status', 'pending')->count(),
            'approved_jobs' => BiraConsultantJob::where('approval_status', 'approved')->count(),
            'total_companies' => BiraConsultantCompany::count(),
            'pending_companies' => BiraConsultantCompany::where('verification_status', 0)->count(),
            'verified_companies' => BiraConsultantCompany::where('verification_status', 1)->count(),
            'total_users' => BiraConsultantUser::where('role', 0)->count(),
            'total_applications' => BiraConsultantApplication::count(),
            'pending_applications' => BiraConsultantApplication::where('status_id', 1)->count(),
            'pending_identity' => BiraConsultantUser::whereNotNull('nid_front_path')->where('identity_verified', 0)->count(),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }
}

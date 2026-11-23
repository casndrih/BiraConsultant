<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QJobsApplication;
use App\Models\QJobsUser;
use App\Models\QJobsUserWorkExperience;
use App\Models\QJobsJobSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApiController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_applications' => QJobsApplication::where('user_id', $user->id)->count(),
            'pending' => QJobsApplication::where('user_id', $user->id)->where('status_id', 1)->count(),
            'reviewed' => QJobsApplication::where('user_id', $user->id)->where('status_id', 2)->count(),
            'interview' => QJobsApplication::where('user_id', $user->id)->where('status_id', 3)->count(),
            'offered' => QJobsApplication::where('user_id', $user->id)->where('status_id', 4)->count(),
            'hired' => QJobsApplication::where('user_id', $user->id)->where('status_id', 5)->count(),
            'rejected' => QJobsApplication::where('user_id', $user->id)->where('status_id', 6)->count(),
            'saved_jobs' => $user->savedJobs()->count(),
            'followed_companies' => $user->followingCompanies()->count(),
        ];

        $recentApplications = QJobsApplication::with(['job.company'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_applications' => $recentApplications,
            ],
        ]);
    }

    public function profile()
    {
        $user = Auth::user()->load(['country', 'skills', 'workExperiences']);
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function applications(Request $request)
    {
        $user = Auth::user();
        $query = QJobsApplication::with(['job.company'])
            ->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status_id', $request->input('status'));
        }

        $applications = $query->latest()->paginate(10);
        return response()->json(['success' => true, 'data' => $applications]);
    }

    public function skills()
    {
        $skills = Auth::user()->skills;
        return response()->json(['success' => true, 'data' => $skills]);
    }

    public function addSkill(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $skill = QJobsJobSkill::firstOrCreate(['name' => $request->name]);
        Auth::user()->skills()->syncWithoutDetaching([$skill->id]);
        return response()->json(['success' => true, 'data' => $skill]);
    }

    public function removeSkill($id)
    {
        Auth::user()->skills()->detach($id);
        return response()->json(['success' => true]);
    }

    public function workExperiences()
    {
        $experiences = Auth::user()->workExperiences()->latest()->get();
        return response()->json(['success' => true, 'data' => $experiences]);
    }

    public function addWorkExperience(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $experience = QJobsUserWorkExperience::create([
            'user_id' => Auth::id(),
            'company_name' => $request->company_name,
            'position' => $request->position,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json(['success' => true, 'data' => $experience]);
    }

    public function deleteWorkExperience($id)
    {
        QJobsUserWorkExperience::where('user_id', Auth::id())->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function followedCompanies()
    {
        $companies = Auth::user()->followingCompanies()->withCount(['jobs'])->get();
        return response()->json(['success' => true, 'data' => $companies]);
    }
}

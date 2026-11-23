<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantJobType;
use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantJobCategory;
use App\Models\BiraConsultantJobExperienceLevel;
use App\Notifications\NewJobFromFollowedCompanyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $categoryId = $request->input('category_id');
        $jobTypeId = $request->input('job_type_id');
        $experienceId = $request->input('experience_id');
        $companyId = $request->input('company_id');

        $query = BiraConsultantJob::with(['company', 'category']);

        // Status filter
        switch ($filter) {
            case 'pending':
                $query->where('approval_status', 'pending');
                break;
            case 'approved':
                $query->where('approval_status', 'approved');
                break;
            case 'rejected':
                $query->where('approval_status', 'rejected');
                break;
            case 'expired':
                $query->where('deadline', '<', now());
                break;
        }

        // Advanced filters
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($jobTypeId) {
            $query->where('job_type_id', $jobTypeId);
        }
        if ($experienceId) {
            $query->where('experience_level_id', $experienceId);
        }
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());

        $counts = [
            'all' => BiraConsultantJob::count(),
            'pending' => BiraConsultantJob::where('approval_status', 'pending')->count(),
            'approved' => BiraConsultantJob::where('approval_status', 'approved')->count(),
            'rejected' => BiraConsultantJob::where('approval_status', 'rejected')->count(),
            'expired' => BiraConsultantJob::where('deadline', '<', now())->count(),
        ];

        $jobTypes = BiraConsultantJobType::orderBy('name')->get();
        $experienceLevels = BiraConsultantJobExperienceLevel::orderBy('name')->get();
        $companies = BiraConsultantCompany::orderBy('name')->get();
        $categories = BiraConsultantJobCategory::orderBy('name')->get();

        // If AJAX request, return only the table body fragment
        if ($request->ajax()) {
            return view('admin.jobs.partials.table', compact('jobs'));
        }

        return view('admin.jobs.index', compact(
            'jobs', 'counts', 'filter',
            'jobTypes', 'experienceLevels', 'companies', 'categories'
        ));
    }

    public function query(Request $request)
    {
        $query = trim($request->input('query-job', ''));

        $jobs = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('location', 'like', '%' . $query . '%')
                  ->orWhereHas('jobType', function ($q2) use ($query) {
                      $q2->where('name', 'like', '%' . $query . '%');
                  })
                  ->orWhereHas('company', function ($q2) use ($query) {
                      $q2->where('name', 'like', '%' . $query . '%');
                  })
                  ->orWhereHas('category', function ($q2) use ($query) {
                      $q2->where('name', 'like', '%' . $query . '%');
                  });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $counts = [
            'all' => BiraConsultantJob::count(),
            'pending' => BiraConsultantJob::where('approval_status', 'pending')->count(),
            'approved' => BiraConsultantJob::where('approval_status', 'approved')->count(),
            'rejected' => BiraConsultantJob::where('approval_status', 'rejected')->count(),
        ];
        $filter = 'all';

        return view('admin.jobs.index', compact('jobs', 'counts', 'filter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_id' => 'required|exists:biraconsultant_companies,id',
            'category_id' => 'required|exists:biraconsultant_job_categories,id',
            'location' => 'nullable|string|max:255',
            'job_type_id' => 'required|exists:biraconsultant_job_types,id',
            'salary' => 'nullable|string|max:100',
            'experience_level_id' => 'required|exists:biraconsultant_job_experience_levels,id',
            'deadline' => 'nullable|date',
            'duration' => 'nullable|integer|min:1|max:365',
            'status' => 'nullable|string|in:0,1',
        ]);

        $job = BiraConsultantJob::create([
            'title' => $request->title,
            'description' => $request->description,
            'company_id' => $request->company_id,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'job_type_id' => $request->job_type_id,
            'salary' => $request->salary,
            'experience_level_id' => $request->experience_level_id,
            'deadline' => $request->deadline,
            'duration' => $request->duration ?? 30,
            'status' => $request->status ?? '1',
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Notify followers
        $company = \App\Models\BiraConsultantCompany::find($request->company_id);
        if ($company) {
            $followers = $company->followers()
                ->where('notify_followed_company_jobs', true)
                ->get();

            $freshJob = BiraConsultantJob::with('company')->find($job->id);
            if ($freshJob) {
                foreach ($followers as $follower) {
                    $follower->notify(new NewJobFromFollowedCompanyNotification($freshJob));
                }
            }
        }

        Session::flash('success', 'Job created and published successfully.');
        return redirect()->route('admin.jobs.index');
    }

    public function edit($slug)
    {
        $job = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])->where('slug', $slug)->firstOrFail();
        $jobTypes = BiraConsultantJobType::orderBy('name')->get();
        $experienceLevels = BiraConsultantJobExperienceLevel::orderBy('name')->get();

        return response()->json([
            'job' => $job,
            'jobTypes' => $jobTypes,
            'experienceLevels' => $experienceLevels,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'edit_job_id' => 'required|exists:biraconsultant_jobs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'company_id' => 'required|exists:biraconsultant_companies,id',
            'category_id' => 'required|exists:biraconsultant_job_categories,id',
            'location' => 'nullable|string|max:255',
            'job_type_id' => 'required|exists:biraconsultant_job_types,id',
            'salary' => 'nullable|string|max:100',
            'experience_level_id' => 'required|exists:biraconsultant_job_experience_levels,id',
            'deadline' => 'nullable|date',
            'duration' => 'nullable|integer|min:1|max:365',
            'status' => 'nullable|string|in:0,1',
        ]);

        $job = BiraConsultantJob::findOrFail($request->edit_job_id);

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'company_id' => $request->company_id,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'job_type_id' => $request->job_type_id,
            'salary' => $request->salary,
            'experience_level_id' => $request->experience_level_id,
            'deadline' => $request->deadline,
            'duration' => $request->duration ?? $job->duration,
            'status' => $request->status ?? '1',
        ]);

        Session::flash('success', 'Job updated successfully.');
        return redirect()->route('admin.jobs.index');
    }

    // ─── Approval Actions ────────────────────────────────────────────────

    public function approve(BiraConsultantJob $job)
    {
        $job->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'status' => 1,
            'rejection_reason' => null,
        ]);

        // Notify followers
        $company = $job->company;
        if ($company) {
            $followers = $company->followers()
                ->where('notify_followed_company_jobs', true)
                ->get();

            $freshJob = BiraConsultantJob::with('company')->find($job->id);
            if ($freshJob) {
                foreach ($followers as $follower) {
                    $follower->notify(new NewJobFromFollowedCompanyNotification($freshJob));
                }
            }
        }

        return redirect()->back()->with('success', "Job \"{$job->title}\" approved and published.");
    }

    public function reject(Request $request, BiraConsultantJob $job)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $job->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', "Job \"{$job->title}\" rejected.");
    }

    public function delete(Request $request)
    {
        $job = BiraConsultantJob::findOrFail($request->delete_job_id);
        $job->delete();

        Session::flash('success', 'Job deleted successfully.');
        return redirect()->back()->with('success', 'Job deleted successfully.');
    }
}

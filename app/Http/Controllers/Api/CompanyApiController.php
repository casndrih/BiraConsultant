<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\UploadLimitHelper;
use App\Models\QJobsJob;
use App\Models\QJobsApplication;
use App\Models\QJobsCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyApiController extends Controller
{
    private function currentCompany(): QJobsCompany
    {
        $company = Auth::user()?->company;
        abort_if(!$company, 404, 'No company found.');
        return $company;
    }

    public function dashboard()
    {
        $company = $this->currentCompany();

        $stats = [
            'total_jobs' => QJobsJob::where('company_id', $company->id)->count(),
            'active_jobs' => QJobsJob::where('company_id', $company->id)->where('status', 1)->where('approval_status', 'approved')->count(),
            'pending_jobs' => QJobsJob::where('company_id', $company->id)->where('approval_status', 'pending')->count(),
            'total_applications' => QJobsApplication::whereIn('job_id', function ($q) use ($company) {
                $q->select('id')->from('qjobs_jobs')->where('company_id', $company->id);
            })->count(),
            'pending_applications' => QJobsApplication::where('status_id', 1)
                ->whereIn('job_id', function ($q) use ($company) {
                    $q->select('id')->from('qjobs_jobs')->where('company_id', $company->id);
                })->count(),
        ];

        $recentApplications = QJobsApplication::with(['job', 'user'])
            ->whereIn('job_id', function ($q) use ($company) {
                $q->select('id')->from('qjobs_jobs')->where('company_id', $company->id);
            })
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'company' => $company,
                'stats' => $stats,
                'recent_applications' => $recentApplications,
            ],
        ]);
    }

    public function applications(Request $request)
    {
        $company = $this->currentCompany();
        $query = QJobsApplication::with(['job', 'user'])
            ->whereIn('job_id', function ($q) use ($company) {
                $q->select('id')->from('qjobs_jobs')->where('company_id', $company->id);
            });

        if ($request->filled('status')) {
            $query->where('status_id', $request->input('status'));
        }

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->input('job_id'));
        }

        $applications = $query->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $applications]);
    }

    public function jobApplications(Request $request, $job)
    {
        $company = $this->currentCompany();
        $job = QJobsJob::where('company_id', $company->id)->findOrFail($job);

        $query = QJobsApplication::with(['user'])
            ->where('job_id', $job->id);

        if ($request->filled('status')) {
            $query->where('status_id', $request->input('status'));
        }

        $applications = $query->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $applications]);
    }

    public function updateStage(Request $request, $application)
    {
        $request->validate(['stage' => 'required|integer|between:1,6']);

        $company = $this->currentCompany();
        $application = QJobsApplication::findOrFail($application);
        $job = QJobsJob::findOrFail($application->job_id);

        if (!$company || $job->company_id !== $company->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $application->update(['status_id' => $request->stage]);
        return response()->json(['success' => true, 'data' => $application]);
    }

    public function verification()
    {
        $company = $this->currentCompany();
        return response()->json([
            'success' => true,
            'data' => [
                'verification_status' => $company->verification_status,
                'is_approved' => (bool) $company->is_approved,
                'registration_doc_path' => $company->registration_doc_path ? Storage::url($company->registration_doc_path) : null,
                'tax_doc_path' => $company->tax_doc_path ? Storage::url($company->tax_doc_path) : null,
            ],
        ]);
    }

    public function settings()
    {
        $company = $this->currentCompany();
        return response()->json(['success' => true, 'data' => $company]);
    }

    /** POST /api/v1/company/settings — update company profile */
    public function updateSettings(Request $request)
    {
        $company = $this->currentCompany();

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'website'      => 'nullable|url|max:255',
            'industry'     => 'nullable|string|max:255',
            'location'     => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:255',
            'address'      => 'nullable|string|max:500',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'description'  => 'nullable|string|max:2000',
            'founded_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'size'         => 'nullable|string|max:50',
            'logo'         => 'nullable|image|max:' . UploadLimitHelper::maxRule('company_logo'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $request->only([
            'name', 'website', 'industry', 'location', 'city', 'address',
            'email', 'phone', 'description', 'founded_year', 'size',
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        $company->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Company settings updated successfully!',
            'data'    => $company->fresh(),
        ]);
    }

    public function jobs(Request $request)
    {
        $company = $this->currentCompany();
        $query = QJobsJob::where('company_id', $company->id);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active' ? 1 : 0);
        }

        $jobs = $query->with(['category', 'jobType'])
            ->withCount('applications')
            ->latest()
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $jobs]);
    }

    public function showJob($job)
    {
        $company = $this->currentCompany();
        $job = QJobsJob::with(['category', 'jobType', 'experienceLevel'])
            ->where('company_id', $company->id)
            ->findOrFail($job);

        return response()->json(['success' => true, 'data' => $job]);
    }

    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:qjobs_job_categories,id',
            'job_type_id' => 'nullable|exists:qjobs_job_types,id',
            'experience_level_id' => 'nullable|exists:qjobs_job_experience_levels,id',
            'duration' => 'nullable|integer|min:1|max:365',
        ]);

        $company = $this->currentCompany();

        $job = QJobsJob::create([
            'company_id' => $company->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'requirements' => $request->requirements,
            'responsibilities' => $request->responsibilities,
            'location' => $request->location,
            'salary' => $request->salary,
            'category_id' => $request->category_id,
            'job_type_id' => $request->job_type_id,
            'experience_level_id' => $request->experience_level_id,
            'duration' => $request->input('duration', 30),
            'approval_status' => 'pending',
            'status' => 1,
        ]);

        return response()->json(['success' => true, 'data' => $job], 201);
    }

    public function updateJob(Request $request, $job)
    {
        $company = $this->currentCompany();
        $job = QJobsJob::where('company_id', $company->id)->findOrFail($job);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:qjobs_job_categories,id',
            'job_type_id' => 'nullable|exists:qjobs_job_types,id',
            'experience_level_id' => 'nullable|exists:qjobs_job_experience_levels,id',
            'duration' => 'nullable|integer|min:1|max:365',
        ]);

        $job->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'requirements' => $request->requirements,
            'responsibilities' => $request->responsibilities,
            'location' => $request->location,
            'salary' => $request->salary,
            'category_id' => $request->category_id,
            'job_type_id' => $request->job_type_id,
            'experience_level_id' => $request->experience_level_id,
            // duration column is NOT NULL DEFAULT '30' — keep existing value when omitted
            'duration' => $request->filled('duration') ? $request->input('duration') : $job->duration,
        ]);

        return response()->json(['success' => true, 'data' => $job]);
    }

    public function toggleJobStatus($job)
    {
        $company = $this->currentCompany();
        $job = QJobsJob::where('company_id', $company->id)->findOrFail($job);
        $newStatus = $job->status ? 0 : 1;
        $job->update(['status' => $newStatus]);
        return response()->json(['success' => true, 'data' => $job]);
    }

    public function deleteJob($job)
    {
        $company = $this->currentCompany();
        $job = QJobsJob::where('company_id', $company->id)->findOrFail($job);
        $job->delete();
        return response()->json(['success' => true]);
    }
}

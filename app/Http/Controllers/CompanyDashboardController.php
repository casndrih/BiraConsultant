<?php

namespace App\Http\Controllers;

use App\Models\QJobsCompany;
use App\Models\QJobsJob;
use App\Models\QJobsApplication;
use App\Models\QJobsApplicationStatus;
use App\Models\QJobsJobType;
use App\Models\QJobsJobCategory;
use App\Helpers\UploadLimitHelper;
use App\Models\QJobsJobExperienceLevel;
use App\Services\JobSpreadsheetImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use RuntimeException;

class CompanyDashboardController extends Controller
{
    private function getCompany()
    {
        return QJobsCompany::where('user_id', Auth::id())->first();
    }

    public function index()
    {
        $user = Auth::user();
        $company = $this->getCompany();

        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        $companyId = $company->id;
        $jobIds = QJobsJob::where('company_id', $companyId)->pluck('id');

        $stats = [
            'total_jobs' => QJobsJob::where('company_id', $companyId)->count(),
            'active_jobs' => QJobsJob::where('company_id', $companyId)->where('status', 1)->where('approval_status', 'approved')->count(),
            'pending_jobs' => QJobsJob::where('company_id', $companyId)->where('approval_status', 'pending')->count(),
            'total_applications' => QJobsApplication::whereIn('job_id', $jobIds)->count(),
            'received' => QJobsApplication::whereIn('job_id', $jobIds)->where('status_id', 1)->count(),
            'under_review' => QJobsApplication::whereIn('job_id', $jobIds)->where('status_id', 2)->count(),
            'interview' => QJobsApplication::whereIn('job_id', $jobIds)->where('status_id', 3)->count(),
            'job_offer' => QJobsApplication::whereIn('job_id', $jobIds)->where('status_id', 4)->count(),
            'hired' => QJobsApplication::whereIn('job_id', $jobIds)->where('status_id', 5)->count(),
            'rejected' => QJobsApplication::whereIn('job_id', $jobIds)->where('status_id', 6)->count(),
        ];

        $recentApplications = QJobsApplication::with(['user.country', 'job', 'status'])
            ->whereIn('job_id', $jobIds)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $jobs = QJobsJob::withCount('applications')
            ->where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('company.dashboard', compact('company', 'stats', 'recentApplications', 'jobs'));
    }

    // ─── Job Posting (Company) ───────────────────────────────────────────

    public function createJob()
    {
        $company = $this->getCompany();
        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        $jobTypes = QJobsJobType::orderBy('name')->get();
        $categories = QJobsJobCategory::orderBy('name')->get();
        $experienceLevels = QJobsJobExperienceLevel::orderBy('name')->get();

        return view('company.jobs.create', compact('company', 'jobTypes', 'categories', 'experienceLevels'));
    }

    public function storeJob(Request $request)
    {
        $company = $this->getCompany();
        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'how_to_apply' => 'nullable|string',
            'category_id' => 'required|exists:qjobs_job_categories,id',
            'location' => 'nullable|string|max:255',
            'job_type_id' => 'required|exists:qjobs_job_types,id',
            'salary' => 'nullable|string|max:100',
            'experience_level_id' => 'required|exists:qjobs_job_experience_levels,id',
            'deadline' => 'nullable|date|after:today',
            'duration' => 'nullable|integer|min:1|max:365',
        ]);

        QJobsJob::create([
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'how_to_apply' => $request->how_to_apply,
            'company_id' => $company->id,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'job_type_id' => $request->job_type_id,
            'salary' => $request->salary,
            'experience_level_id' => $request->experience_level_id,
            'deadline' => $request->deadline,
            'duration' => $request->duration ?? 30,
            'status' => 1,
            'approval_status' => 'pending',
        ]);

        return redirect()->route('company.dashboard')->with('success', 'Job posted successfully! It will be reviewed by our team and published upon approval.');
    }

    public function showBulkJobImport()
    {
        $company = $this->getCompany();
        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        $categories = QJobsJobCategory::orderBy('name')->pluck('name');
        $jobTypes = QJobsJobType::orderBy('name')->pluck('name');
        $experienceLevels = QJobsJobExperienceLevel::orderBy('name')->pluck('name');

        return view('company.jobs.import', compact('company', 'categories', 'jobTypes', 'experienceLevels'));
    }

    public function downloadBulkJobTemplate()
    {
        $headers = [
            'title', 'description', 'category', 'job_type', 'experience_level',
            'location', 'salary', 'deadline', 'duration', 'requirements', 'how_to_apply',
        ];

        return response()->streamDownload(function () use ($headers) {
            $output = fopen('php://output', 'wb');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, $headers);
            fclose($output);
        }, 'qjobs-bulk-jobs-template.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function importJobs(Request $request, JobSpreadsheetImporter $importer)
    {
        $company = $this->getCompany();
        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        $request->validate(['jobs_file' => 'required|file|max:10240']);

        try {
            $spreadsheet = $importer->read($request->file('jobs_file'));
        } catch (RuntimeException $exception) {
            return back()->withErrors(['jobs_file' => $exception->getMessage()])->withInput();
        }

        $categories = QJobsJobCategory::all(['id', 'name']);
        $jobTypes = QJobsJobType::all(['id', 'name']);
        $experienceLevels = QJobsJobExperienceLevel::all(['id', 'name']);
        $jobsToCreate = [];
        $rowErrors = [];

        foreach ($spreadsheet['rows'] as $spreadsheetRow) {
            $row = $spreadsheetRow['row'];
            $values = $spreadsheetRow['data'];
            $data = [
                'title' => trim($values['title'] ?? ''),
                'description' => trim($values['description'] ?? ''),
                'requirements' => trim($values['requirements'] ?? '') ?: null,
                'how_to_apply' => trim($values['how_to_apply'] ?? '') ?: null,
                'category_id' => $this->resolveImportLookup($values['category'] ?? '', $categories),
                'job_type_id' => $this->resolveImportLookup($values['job_type'] ?? '', $jobTypes),
                'experience_level_id' => $this->resolveImportLookup($values['experience_level'] ?? '', $experienceLevels),
                'location' => trim($values['location'] ?? '') ?: null,
                'salary' => trim($values['salary'] ?? '') ?: null,
                'deadline' => $this->normalizeSpreadsheetDate($values['deadline'] ?? ''),
                'duration' => trim($values['duration'] ?? '') ?: 30,
            ];

            $validator = Validator::make($data, [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:20000',
                'requirements' => 'nullable|string|max:20000',
                'how_to_apply' => 'nullable|string|max:10000',
                'category_id' => 'required|integer|exists:qjobs_job_categories,id',
                'job_type_id' => 'required|integer|exists:qjobs_job_types,id',
                'experience_level_id' => 'required|integer|exists:qjobs_job_experience_levels,id',
                'location' => 'nullable|string|max:255',
                'salary' => 'nullable|string|max:100',
                'deadline' => 'nullable|date|after:today',
                'duration' => 'required|integer|min:1|max:365',
            ]);

            if ($validator->fails()) {
                $rowErrors[] = ['row' => $row, 'messages' => $validator->errors()->all()];
                continue;
            }

            $jobsToCreate[] = $validator->validated();
        }

        if ($jobsToCreate !== []) {
            DB::transaction(function () use ($jobsToCreate, $company) {
                foreach ($jobsToCreate as $job) {
                    QJobsJob::create(array_merge($job, [
                        'company_id' => $company->id,
                        'status' => 1,
                        'approval_status' => 'pending',
                    ]));
                }
            });
        }

        return redirect()->route('company.jobs.import')
            ->with('import_summary', [
                'imported' => count($jobsToCreate),
                'skipped' => count($rowErrors),
            ])
            ->with('import_errors', array_slice($rowErrors, 0, 50))
            ->with('import_errors_truncated', count($rowErrors) > 50);
    }

    private function resolveImportLookup(string $value, $records): ?int
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $match = $records->first(function ($record) use ($value) {
            if (ctype_digit($value) && (int) $record->id === (int) $value) {
                return true;
            }

            return mb_strtolower(trim($record->name)) === mb_strtolower($value);
        });

        return $match?->id;
    }

    private function normalizeSpreadsheetDate(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        try {
            if (is_numeric($value) && (float) $value >= 20000 && (float) $value <= 100000) {
                $seconds = ((int) round((float) $value) - 25569) * 86400;
                return Carbon::createFromTimestampUTC($seconds)->toDateString();
            }

            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return $value;
        }
    }

    public function editJob(QJobsJob $job)
    {
        $company = $this->getCompany();
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $jobTypes = QJobsJobType::orderBy('name')->get();
        $categories = QJobsJobCategory::orderBy('name')->get();
        $experienceLevels = QJobsJobExperienceLevel::orderBy('name')->get();

        return view('company.jobs.edit', compact('company', 'job', 'jobTypes', 'categories', 'experienceLevels'));
    }

    public function updateJob(Request $request, QJobsJob $job)
    {
        $company = $this->getCompany();
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'how_to_apply' => 'nullable|string',
            'category_id' => 'required|exists:qjobs_job_categories,id',
            'location' => 'nullable|string|max:255',
            'job_type_id' => 'required|exists:qjobs_job_types,id',
            'salary' => 'nullable|string|max:100',
            'experience_level_id' => 'required|exists:qjobs_job_experience_levels,id',
            'deadline' => 'nullable|date',
            'duration' => 'nullable|integer|min:1|max:365',
        ]);

        // If job was rejected, re-submitting resets to pending
        $approvalStatus = $job->approval_status;
        if ($approvalStatus === 'rejected') {
            $approvalStatus = 'pending';
        }

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'how_to_apply' => $request->how_to_apply,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'job_type_id' => $request->job_type_id,
            'salary' => $request->salary,
            'experience_level_id' => $request->experience_level_id,
            'deadline' => $request->deadline,
            'duration' => $request->duration ?? $job->duration,
            'approval_status' => $approvalStatus,
        ]);

        return redirect()->route('company.dashboard')->with('success', 'Job updated successfully!' . ($approvalStatus === 'pending' ? ' It will be re-reviewed by our team.' : ''));
    }

    public function toggleJobStatus(QJobsJob $job)
    {
        $company = $this->getCompany();
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $job->update(['status' => !$job->status]);

        $label = $job->status ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Job {$label} successfully.");
    }

    public function deleteJob(QJobsJob $job)
    {
        $company = $this->getCompany();
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $job->delete();

        return redirect()->route('company.dashboard')->with('success', 'Job deleted successfully.');
    }

    // ─── Applications ────────────────────────────────────────────────────

    public function applications()
    {
        $company = $this->getCompany();
        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        $jobIds = QJobsJob::where('company_id', $company->id)->pluck('id');

        $query = QJobsApplication::with(['user.country', 'job', 'status'])
            ->whereIn('job_id', $jobIds);

        if (request('status')) {
            $query->where('status_id', request('status'));
        }
        if (request('job_id')) {
            $query->where('job_id', request('job_id'));
        }
        if (request('search')) {
            $search = request('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());
        $jobs = QJobsJob::where('company_id', $company->id)->orderBy('title')->get();
        $statuses = QJobsApplicationStatus::orderBy('id')->get();

        return view('company.applications', compact('company', 'applications', 'jobs', 'statuses'));
    }

    // Applications AJAX listing (returns JSON with HTML)
    public function applicationsList()
    {
        $company = $this->getCompany();
        if (!$company) {
            return response()->json(['error' => 'No company'], 403);
        }

        $jobIds = QJobsJob::where('company_id', $company->id)->pluck('id');

        $query = QJobsApplication::with(['user.country', 'job', 'status'])
            ->whereIn('job_id', $jobIds);

        if (request('status')) {
            $query->where('status_id', request('status'));
        }
        if (request('job_id')) {
            $query->where('job_id', request('job_id'));
        }
        if (request('search')) {
            $search = request('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());
        $statuses = QJobsApplicationStatus::orderBy('id')->get();

        $html = view('company.applications-list', compact('applications', 'statuses'))->render();
        $pagination = $applications->links()->toHtml();

        return response()->json([
            'html' => $html,
            'total' => $applications->total(),
            'pagination' => $pagination,
        ]);
    }

    public function jobApplications(QJobsJob $job)
    {
        $company = $this->getCompany();
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $query = QJobsApplication::with(['user.country', 'status'])
            ->where('job_id', $job->id);

        if (request('status')) {
            $query->where('status_id', request('status'));
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());
        $statuses = QJobsApplicationStatus::orderBy('id')->get();

        return view('company.job-applications', compact('company', 'job', 'applications', 'statuses'));
    }

    public function updateStage(Request $request, QJobsApplication $application)
    {
        $company = $this->getCompany();
        $job = QJobsJob::findOrFail($application->job_id);

        if (!$company || $job->company_id !== $company->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status_id' => 'required|integer|between:1,6',
        ]);

        $application->update([
            'status_id' => $request->status_id,
            'reviewed_at' => now(),
        ]);

        if ($request->ajax()) {
            $application->load('status');
            return response()->json([
                'success' => true,
                'status_label' => $application->status->label ?? 'Unknown',
                'status_color' => $application->status->color_class ?? 'bg-gray-100 text-gray-800',
                'message' => 'Application status updated successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Application status updated to: ' . (QJobsApplicationStatus::find($request->status_id)->label ?? 'Unknown'));
    }

    public function downloadFile(QJobsApplication $application, $type)
    {
        $company = $this->getCompany();
        $job = QJobsJob::findOrFail($application->job_id);

        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $fieldMap = [
            'cv' => 'cv_path',
            'resume' => 'resume_path',
            'cover_letter' => 'cover_letter_path',
        ];

        if (!isset($fieldMap[$type])) {
            abort(404);
        }

        $path = $application->{$fieldMap[$type]};
        if (!$path || !Storage::disk('public')->exists($path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($path);
    }

    public function verification()
    {
        $user = Auth::user();
        $company = $this->getCompany();

        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        return view('company.verification', compact('company'));
    }

    public function uploadVerification(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string|max:100',
            'registration_doc'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:' . UploadLimitHelper::maxRule('company_docs'),
            'tax_id_number'       => 'required|string|max:100',
            'tax_doc'             => 'required|file|mimes:pdf,jpg,jpeg,png|max:' . UploadLimitHelper::maxRule('company_docs'),
        ]);

        $user = Auth::user();
        $company = $this->getCompany();

        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        $regDocPath = $request->file('registration_doc')->store('company_docs/' . $company->id, 'public');
        $taxDocPath = $request->file('tax_doc')->store('company_docs/' . $company->id, 'public');

        $company->update([
            'registration_number' => $request->registration_number,
            'registration_doc_path' => $regDocPath,
            'tax_id_number' => $request->tax_id_number,
            'tax_doc_path' => $taxDocPath,
            'verification_status' => 0,
            'verification_rejection_reason' => null,
            'verified_at' => null,
            'verified_by' => null,
        ]);

        return redirect()->route('company.verification')->with('success', 'Verification documents submitted! Our team will review them within 24-48 hours.');
    }

    // ─── Company Profile Settings ─────────────────────────────────────────

    public function settings()
    {
        $user = Auth::user();
        $company = $this->getCompany();

        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        $countries = \App\Models\QJobsCountry::orderBy('name')->get();

        return view('company.settings', compact('company', 'countries'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $company = $this->getCompany();

        if (!$company) {
            return redirect()->route('company.register')->with('error', 'Please register your company first.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country_id' => 'nullable|exists:qjobs_countries,id',
            'size' => 'nullable|string|max:50',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'logo' => 'nullable|image|max:' . UploadLimitHelper::maxRule('company_logo'),
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'website', 'industry', 'description',
            'address', 'city', 'country_id', 'size', 'founded_year',
            'latitude', 'longitude',
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        return redirect()->route('company.settings')->with('success', 'Company profile updated successfully!');
    }
}

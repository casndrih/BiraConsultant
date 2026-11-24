<?php

namespace App\Http\Controllers;

use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantBlog;
use App\Models\BiraConsultantBlogCategory;
use App\Models\BiraConsultantJobCategory;
use App\Models\BiraConsultantJobType;
use App\Models\BiraConsultantJobExperienceLevel;
use App\Models\BiraConsultantApplication;
use App\Models\BiraConsultantApplicationStatus;
use App\Models\BiraConsultantUser;
use App\Models\BiraConsultantCountry;
use App\Models\BiraConsultantUserWorkExperience;
use App\Notifications\CompanyFollowedNotification;
use App\Notifications\NewJobFromFollowedCompanyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\UploadLimitHelper;
use App\Models\BiraConsultantContactMessage;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
    public function index()
    {
        $latestJobs = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active()
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $featuredJobs = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active()
            ->inRandomOrder()
            ->take(4)
            ->get();

        $categories = BiraConsultantJobCategory::withCount(['jobs' => function ($query) { $query->active(); }])->orderBy('name')->get();
        $companies = BiraConsultantCompany::publiclyVisible()->withCount(['jobs' => function ($q) { $q->active(); }])->orderBy('name')->take(6)->get();
        $jobTypes = BiraConsultantJobType::orderBy('name')->get();
        $experienceLevels = BiraConsultantJobExperienceLevel::orderBy('name')->get();
        $totalJobs = BiraConsultantJob::active()->count();
        $totalCompanies = BiraConsultantCompany::publiclyVisible()->count();

        $mapLocationDefinitions = [
            ['name' => 'Morobe / Lae', 'query' => 'Morobe, Lae', 'terms' => ['morobe', 'lae'], 'x' => 34.2, 'y' => 52.8, 'badge_offset' => -10],
            ['name' => 'Port Moresby / NCD', 'query' => 'Port Moresby, NCD, National Capital District', 'terms' => ['port moresby', 'ncd', 'national capital district'], 'x' => 35.1, 'y' => 76.2, 'badge_offset' => 0],
            ['name' => 'Goroka', 'query' => 'Goroka, Eastern Highlands', 'terms' => ['goroka', 'eastern highlands'], 'x' => 25.8, 'y' => 47.2, 'badge_offset' => -16],
            ['name' => 'Madang', 'query' => 'Madang', 'terms' => ['madang'], 'x' => 27.9, 'y' => 39.8, 'badge_offset' => 16],
            ['name' => 'Manus / Lorengau', 'query' => 'Manus, Lorengau', 'terms' => ['manus', 'lorengau'], 'x' => 35.6, 'y' => 12.2, 'badge_offset' => 0],
            ['name' => 'Mount Hagen', 'query' => 'Mount Hagen, Mt Hagen, Western Highlands', 'terms' => ['mount hagen', 'mt hagen', 'western highlands'], 'x' => 19.8, 'y' => 45.3, 'badge_offset' => -12],
            ['name' => 'Wewak', 'query' => 'Wewak, East Sepik', 'terms' => ['wewak', 'east sepik'], 'x' => 16.7, 'y' => 25.4, 'badge_offset' => 0],
            ['name' => 'Kokopo / Rabaul', 'query' => 'Kokopo, Rabaul, East New Britain', 'terms' => ['kokopo', 'rabaul', 'east new britain'], 'x' => 61.4, 'y' => 32.2, 'badge_offset' => 0],
        ];

        $activeJobLocationCounts = BiraConsultantJob::active()
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->selectRaw('LOWER(location) as normalized_location, COUNT(*) as total')
            ->groupByRaw('LOWER(location)')
            ->get();

        $jobMapLocations = collect($mapLocationDefinitions)->map(function ($pin) use ($activeJobLocationCounts) {
            $pin['count'] = $activeJobLocationCounts->sum(function ($entry) use ($pin) {
                foreach ($pin['terms'] as $term) {
                    $pattern = '/(?<![a-z0-9])' . preg_quote($term, '/') . '(?![a-z0-9])/';
                    if (preg_match($pattern, $entry->normalized_location)) {
                        return (int) $entry->total;
                    }
                }

                return 0;
            });

            return $pin;
        });

        return view('home.index', compact(
            'latestJobs', 'featuredJobs', 'categories', 'companies',
            'totalJobs', 'totalCompanies', 'jobTypes', 'experienceLevels', 'jobMapLocations'
        ));

    }

    // Job Listing Page
    public function jobs(Request $request)
    {
        $query = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('job_type_id', $request->type);
        }

        if ($request->filled('experience')) {
            $query->where('experience_level_id', $request->experience);
        }

        $locationTerms = collect(preg_split('/[,|]/', (string) $request->input('location', '')))
            ->map(fn ($term) => trim($term))
            ->filter()
            ->unique()
            ->values();

        if ($locationTerms->isNotEmpty()) {
            $query->where(function ($locationQuery) use ($locationTerms) {
                foreach ($locationTerms as $term) {
                    $locationQuery->orWhere('location', 'like', '%' . $term . '%');
                }
            });
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(12)->appends($request->query());
        $categories = BiraConsultantJobCategory::orderBy('name')->get();
        $jobTypes = BiraConsultantJobType::orderBy('name')->get();
        $experienceLevels = BiraConsultantJobExperienceLevel::orderBy('name')->get();
        $locations = BiraConsultantJob::active()->distinct()->pluck('location')->filter()->take(10);

        return view('jobs.index', compact('jobs', 'categories', 'jobTypes', 'experienceLevels', 'locations'));
    }

    // Jobs AJAX listing (returns JSON with HTML)
    public function jobsList(Request $request)
    {
        $query = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('type')) {
            $query->where('job_type_id', $request->type);
        }
        if ($request->filled('experience')) {
            $query->where('experience_level_id', $request->experience);
        }
        $locationTerms = collect(preg_split('/[,|]/', (string) $request->input('location', '')))
            ->map(fn ($term) => trim($term))
            ->filter()
            ->unique()
            ->values();

        if ($locationTerms->isNotEmpty()) {
            $query->where(function ($locationQuery) use ($locationTerms) {
                foreach ($locationTerms as $term) {
                    $locationQuery->orWhere('location', 'like', '%' . $term . '%');
                }
            });
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(9)->appends($request->query());

        $html = '';
        foreach ($jobs as $job) {
            $html .= view('components.job-card', [
                'job' => $job,
                'showSalary' => true,
                'showApplyButton' => true
            ])->render();
        }

        $pagination = $jobs->links()->toHtml();

        return response()->json([
            'html' => $html,
            'total' => $jobs->total(),
            'from' => $jobs->firstItem(),
            'to' => $jobs->lastItem(),
            'pagination' => $pagination,
        ]);
    }

    // Job Detail Page
    public function jobDetail($id)
    {
        $job = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active()
            ->findOrFail($id);

        // Get similar jobs
        $similarJobs = BiraConsultantJob::with(['company'])
            ->where('category_id', $job->category_id)
            ->where('id', '!=', $job->id)
            ->active()
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Check if user has applied/saved
        $hasApplied = false;
        $hasSaved = false;
        if (Auth::check()) {
            $hasApplied = BiraConsultantApplication::where('user_id', Auth::id())
                ->where('job_id', $job->id)
                ->exists();
            $hasSaved = Auth::user()->hasSavedJob($job->id);
        }

        return view('jobs.detail', compact('job', 'similarJobs', 'hasApplied', 'hasSaved'));
    }

    // Job Detail by Slug
    public function jobDetailBySlug($slug)
    {
        $job = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        // Redirect to the old ID-based URL format for backward compatibility
        // Or just render the view directly
        $similarJobs = BiraConsultantJob::with(['company'])
            ->where('category_id', $job->category_id)
            ->where('id', '!=', $job->id)
            ->active()
            ->inRandomOrder()
            ->take(4)
            ->get();

        $hasApplied = false;
        $hasSaved = false;
        if (Auth::check()) {
            $hasApplied = BiraConsultantApplication::where('user_id', Auth::id())
                ->where('job_id', $job->id)
                ->exists();
            $hasSaved = Auth::user()->hasSavedJob($job->id);
        }

        return view('jobs.detail', compact('job', 'similarJobs', 'hasApplied', 'hasSaved'));
    }

    // Save/Unsave Job
    public function toggleSaveJob($jobId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $job = BiraConsultantJob::findOrFail($jobId);

        if ($user->hasSavedJob($jobId)) {
            $user->savedJobs()->detach($jobId);
            return response()->json(['saved' => false, 'message' => 'Job removed from saved list']);
        } else {
            $user->savedJobs()->attach($jobId);
            return response()->json(['saved' => true, 'message' => 'Job saved successfully']);
        }
    }

    // Company Listing Page
    public function companies(Request $request)
    {
        $query = BiraConsultantCompany::publiclyVisible()->withCount(['jobs' => function($q) {
            $q->active();
        }]);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('industry', 'like', '%' . $request->search . '%');
            });
        }

        $companies = $query->orderBy('name')->paginate(12)->appends($request->query());
        return view('companies.index', compact('companies'));
    }

    // Companies AJAX listing (returns JSON with HTML)
    public function companiesList(Request $request)
    {
        $query = BiraConsultantCompany::publiclyVisible()->withCount(['jobs' => function($q) {
            $q->active();
        }]);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('industry', 'like', '%' . $request->search . '%');
            });
        }

        $companies = $query->orderBy('name')->paginate(6)->appends($request->query());

        $html = '';
        foreach ($companies as $company) {
            $html .= view('components.company-card', ['company' => $company])->render();
        }

        $pagination = $companies->links()->toHtml();

        return response()->json([
            'html' => $html,
            'total' => $companies->total(),
            'from' => $companies->firstItem(),
            'to' => $companies->lastItem(),
            'pagination' => $pagination,
        ]);
    }

    public function latestJobsPartial(Request $request)
    {
        $query = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active();

        // Apply filters if present
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('type')) {
            $query->where('job_type_id', $request->type);
        }
        if ($request->filled('experience')) {
            $query->where('experience_level_id', $request->experience);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%')
                  ->orWhereHas('company', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $latestJobs = $query->orderBy('created_at', 'desc')->take(8)->get();
        return view('partials.latest-jobs', compact('latestJobs'));
    }

    // Company Detail Page
    public function companyDetail($id)
    {
        $company = BiraConsultantCompany::publiclyVisible()->findOrFail($id);

        $jobs = BiraConsultantJob::with(['category', 'jobType', 'experienceLevel'])
            ->where('company_id', $company->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        $mapEnabled = \App\Models\BiraConsultantSetting::getValue('google_maps_enabled', '0') === '1';
        $mapApiKey = \App\Models\BiraConsultantSetting::getValue('google_maps_api_key', '');

        return view('companies.detail', compact('company', 'jobs', 'mapEnabled', 'mapApiKey'));
    }

    // Company Detail by Slug
    public function companyDetailBySlug($slug)
    {
        $company = BiraConsultantCompany::publiclyVisible()->where('slug', $slug)->firstOrFail();

        $jobs = BiraConsultantJob::with(['category', 'jobType', 'experienceLevel'])
            ->where('company_id', $company->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        $mapEnabled = \App\Models\BiraConsultantSetting::getValue('google_maps_enabled', '0') === '1';
        $mapApiKey = \App\Models\BiraConsultantSetting::getValue('google_maps_api_key', '');

        return view('companies.detail', compact('company', 'jobs', 'mapEnabled', 'mapApiKey'));
    }

    public function companyDetailByQuery(Request $request)
    {
        $id = $request->input('id');
        if (!$id) {
            return redirect()->route('companies');
        }
        $company = BiraConsultantCompany::publiclyVisible()->findOrFail($id);

        $jobs = BiraConsultantJob::with(['category', 'jobType', 'experienceLevel'])
            ->where('company_id', $company->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        $mapEnabled = \App\Models\BiraConsultantSetting::getValue('google_maps_enabled', '0') === '1';
        $mapApiKey = \App\Models\BiraConsultantSetting::getValue('google_maps_api_key', '');

        return view('companies.detail', compact('company', 'jobs', 'mapEnabled', 'mapApiKey'));
    }

    public function featuredJobsPartial()
    {
        $featuredJobs = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active()
            ->inRandomOrder()
            ->take(4)
            ->get();
        return view('partials.featured-jobs', compact('featuredJobs'));
    }

    public function jobDetailByQuery(Request $request)
    {
        $id = $request->input('id');
        if (!$id) {
            return redirect()->route('jobs');
        }
        return $this->jobDetail($id);
    }

    public function homeCompaniesPartial()
    {
        $companies = BiraConsultantCompany::publiclyVisible()->withCount(['jobs' => function ($q) { $q->where('status', 1)->where('approval_status', 'approved'); }])->orderBy('name')->take(6)->get();
        return view('partials.companies', compact('companies'));
    }

    // Search Page
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $jobs = BiraConsultantJob::with(['company', 'category'])
            ->active()
            ->when($query, function($q) use ($query) {
                $q->where(function($sub) use ($query) {
                    $sub->where('title', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%')
                        ->orWhere('location', 'like', '%' . $query . '%')
                        ->orWhereHas('company', function($q2) use ($query) {
                            $q2->where('name', 'like', '%' . $query . '%');
                        })
                        ->orWhereHas('category', function($q2) use ($query) {
                            $q2->where('name', 'like', '%' . $query . '%');
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12)->appends($request->query());

        return view('search.results', compact('jobs', 'query'));
    }

    // User Dashboard
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user();
        $applications = $user->applications()->with(['job.company', 'status'])->orderBy('created_at', 'desc')->take(5)->get();
        $savedJobs = $user->savedJobs()->with(['company', 'category'])->orderBy('biraconsultant_saved_jobs.created_at', 'desc')->take(5)->get();

        $stats = [
            'total_applications' => $user->applications()->count(),
            'received' => $user->applications()->where('status_id', 1)->count(),
            'under_review' => $user->applications()->where('status_id', 2)->count(),
            'interview' => $user->applications()->where('status_id', 3)->count(),
            'job_offer' => $user->applications()->where('status_id', 4)->count(),
            'hired' => $user->applications()->where('status_id', 5)->count(),
            'rejected' => $user->applications()->where('status_id', 6)->count(),
            'saved_jobs' => $user->savedJobs()->count(),
        ];

        $recommendedJobs = BiraConsultantJob::with(['company', 'category'])
            ->active()
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('user.dashboard', compact('user', 'applications', 'savedJobs', 'stats', 'recommendedJobs'));
    }

    // User Profile
    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user()->load(['country', 'skills', 'workExperiences']);
        $countries = BiraConsultantCountry::orderBy('name')->get();
        $workExperiences = $user->workExperiences()->orderBy('start_date', 'desc')->get();
        $allSkills = \App\Models\BiraConsultantJobSkill::orderBy('name')->get();

        return view('user.profile', compact('user', 'countries', 'workExperiences', 'allSkills'));
    }

    // Update User Profile
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:biraconsultant_users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'country_id' => 'nullable|exists:biraconsultant_countries,id',
            'state' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|string|max:15',
            'image' => 'nullable|image|max:' . UploadLimitHelper::maxRule('profile_image'),
            'cover' => 'nullable|image|max:' . UploadLimitHelper::maxRule('profile_cover_image'),
        ]);

        $data = $request->only(['name', 'email', 'phone', 'bio', 'website', 'country_id', 'state', 'birthday', 'gender']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($user->image) {
                $oldImage = parse_url($user->image, PHP_URL_PATH) ?: $user->image;
                Storage::disk('public')->delete(preg_replace('#^/?storage/#', '', $oldImage));
            }
            $data['image'] = $request->file('image')->store('profiles', 'public');
        }

        if ($request->hasFile('cover')) {
            if ($user->cover) {
                $oldCover = parse_url($user->cover, PHP_URL_PATH) ?: $user->cover;
                Storage::disk('public')->delete(preg_replace('#^/?storage/#', '', $oldCover));
            }
            $data['cover'] = $request->file('cover')->store('profiles/covers', 'public');
        }

        $user->update($data);

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
    }

    // Add Work Experience
    public function addWorkExperience(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $request->validate([
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        BiraConsultantUserWorkExperience::create([
            'user_id' => Auth::id(),
            'company_name' => $request->company_name,
            'position' => $request->position,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('user.profile')->with('success', 'Work experience added!');
    }

    // Delete Work Experience
    public function deleteWorkExperience($id)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $experience = BiraConsultantUserWorkExperience::where('user_id', Auth::id())->findOrFail($id);
        $experience->delete();

        return redirect()->route('user.profile')->with('success', 'Work experience removed!');
    }

    // Add Skill
    public function addSkill(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'skill_name' => 'required|string|max:100',
        ]);

        $skill = \App\Models\BiraConsultantJobSkill::firstOrCreate(['name' => $request->skill_name]);
        Auth::user()->skills()->syncWithoutDetaching([$skill->id]);

        return response()->json(['success' => true, 'skill' => $skill]);
    }

    // Remove Skill
    public function removeSkill(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'skill_id' => 'required|exists:biraconsultant_job_skills,id',
        ]);

        Auth::user()->skills()->detach($request->skill_id);

        return response()->json(['success' => true]);
    }

    // User Applications
    public function applications(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user();
        $query = $user->applications()->with(['job.company', 'status']);

        if ($request->filled('status')) {
            $statusMap = ['pending' => 1, 'received' => 1, 'reviewing' => 2, 'interview' => 3, 'accepted' => 4, 'job_offer' => 4, 'hired' => 5, 'rejected' => 6];
            if (isset($statusMap[$request->status])) {
                $query->where('status_id', $statusMap[$request->status]);
            }
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('user.applications', compact('applications'));
    }

    // Apply for Job
    public function applyJob(Request $request, $job_id)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user();
        $job = BiraConsultantJob::active()->findOrFail($job_id);

        // Check if already applied
        if ($user->hasAppliedTo($job->id)) {
            return redirect()->route('job.detail', $job_id)->with('error', 'You have already applied for this job.');
        }

        $request->validate([
            'cover_letter' => 'required|string|min:50|max:5000',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:' . UploadLimitHelper::maxRule('application_files'),
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:' . UploadLimitHelper::maxRule('application_files'),
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:' . UploadLimitHelper::maxRule('application_files'),
        ]);

        $data = [
            'user_id' => $user->id,
            'job_id' => $job->id,
            'status_id' => 1, // Received
            'cover_letter' => $request->cover_letter,
        ];

        // Handle file uploads
        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('applications/' . $user->id . '/cv', 'public');
        }
        if ($request->hasFile('resume')) {
            $data['resume_path'] = $request->file('resume')->store('applications/' . $user->id . '/resume', 'public');
        }
        if ($request->hasFile('cover_letter_file')) {
            $data['cover_letter_path'] = $request->file('cover_letter_file')->store('applications/' . $user->id . '/cover_letter', 'public');
        }

        BiraConsultantApplication::create($data);

        return redirect()->route('user.applications')->with('success', 'Application submitted successfully! The company will review your profile.');
    }

    // Withdraw Application
    public function withdrawApplication($id)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $application = BiraConsultantApplication::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status_id', 1) // Can only withdraw pending applications
            ->firstOrFail();

        $application->delete();

        return redirect()->route('user.applications')->with('success', 'Application withdrawn.');
    }

    // Company Registration
    public function showCompanyRegister()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user();

        // If user already has a company, redirect to company dashboard
        if ($user->company) {
            return redirect()->route('company.dashboard')->with('info', 'You already have a registered company.');
        }

        $countries = BiraConsultantCountry::orderBy('name')->get();
        return view('companies.register', compact('countries'));
    }

    public function registerCompany(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user();

        // If user already has a company, redirect to company dashboard
        if ($user->company) {
            return redirect()->route('company.dashboard')->with('info', 'You already have a registered company.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'website' => 'nullable|url|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country_id' => 'nullable|exists:biraconsultant_countries,id',
            'industry' => 'nullable|string|max:100',
            'logo' => 'nullable|image|max:' . UploadLimitHelper::maxRule('company_logo'),
        ]);

        $user = Auth::user();

        $company = BiraConsultantCompany::create([
            'name' => $request->name,
            'description' => $request->description,
            'website' => $request->website,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country_id' => $request->country_id,
            'industry' => $request->industry,
            'user_id' => $user->id,
            'is_approved' => 0,
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $company->logo = $logoPath;
            $company->save();
        }

        return redirect()->route('company.dashboard')->with('success', 'Company registered successfully! It will be reviewed by our team.');
    }

    // Blog Pages
    public function blog(Request $request)
    {
        $query = BiraConsultantBlog::with(['author', 'category'])
            ->where('status', 'published');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $blogs = $query->orderBy('published_at', 'desc')->paginate(9)->appends($request->query());
        $categories = BiraConsultantBlogCategory::withCount('blogs')->orderBy('name')->get();

        return view('blog.index', compact('blogs', 'categories'));
    }

    public function blogDetail($slug)
    {
        $blog = BiraConsultantBlog::with(['author', 'category'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedBlogs = BiraConsultantBlog::where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('blog.detail', compact('blog', 'relatedBlogs'));
    }


    // About Page
    public function about()
    {
        return view('pages.about');
    }

    // Advertising information and sponsored placement enquiries.
    public function advertising()
    {
        return view('pages.advertising');
    }

    // Privacy Policy Page (DB-driven)
    public function privacy()
    {
        $policy = \App\Models\BiraConsultantSetting::where('key', 'privacy_policy')->value('value');
        if (empty($policy)) {
            $policy = '<p>Our privacy policy is being updated. Please check back soon.</p>';
        }
        return view('pages.privacy', compact('policy'));
    }

    // Terms of Service Page (DB-driven)
    public function terms()
    {
        $terms = \App\Models\BiraConsultantSetting::where('key', 'terms_of_service')->value('value');
        if (empty($terms)) {
            $terms = '<p>Our terms of service are being updated. Please check back soon.</p>';
        }
        return view('pages.terms', compact('terms'));
    }

    // Contact Page
    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:20|max:5000',
            'website' => 'nullable|size:0',
        ]);

        $contactMessage = BiraConsultantContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        $recipient = config('mail.contact_to');
        if (is_string($recipient) && filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($recipient)->send(new ContactFormMail($contactMessage));
            } catch (\Throwable $exception) {
                Log::error('Contact form notification delivery failed.', [
                    'contact_message_id' => $contactMessage->id,
                    'exception' => $exception::class,
                ]);

                if (app()->environment('testing')) {
                    throw $exception;
                }
            }
                ]);
            }
        }

        return redirect()->route('contact')->with('success', 'Thank you. Your message has been received and saved.');
    }

    // Change Password
    public function changePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('user.profile')->with('success', 'Password changed successfully!');
    }

    // ===== Identity Verification =====

    public function uploadIdentity(Request $request)
    {
        $request->validate([
            'nid_number' => 'required|string|max:50',
            'nid_front'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:' . UploadLimitHelper::maxRule('nid_docs'),
            'nid_back'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:' . UploadLimitHelper::maxRule('nid_docs'),
        ]);

        $user = Auth::user();

        // Store NID images in storage/identity/{user_id}/
        $frontPath = $request->file('nid_front')->store('identity/' . $user->id, 'public');
        $backPath  = $request->file('nid_back')->store('identity/' . $user->id, 'public');

        $user->update([
            'nid_number'            => $request->nid_number,
            'identity_document_type' => 'nid',
            'nid_front_path'        => $frontPath,
            'nid_back_path'         => $backPath,
            'identity_verified'     => 0, // Reset to pending
            'identity_rejection_reason' => null,
            'identity_verified_at'  => null,
        ]);

        return redirect()->route('user.profile')->with('success', 'Identity documents submitted successfully! Our team will review them within 24-48 hours.');
    }

    // ===== Phone Verification =====

    public function sendPhoneCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'phone' => $request->phone,
            'phone_verification_code' => $code,
            'phone_verified' => 0,
        ]);

        // Try to send SMS (falls back to log driver)
        try {
            $smsDriver = \App\Models\BiraConsultantSetting::where('key', 'verification_sms_driver')->value('value') ?? 'log';
            $template  = \App\Models\BiraConsultantSetting::where('key', 'sms_verification_template')->value('value') ?? 'Your BiraConsultant verification code is: {code}';
            $message   = str_replace('{code}', $code, $template);

            if ($smsDriver === 'log') {
                \Illuminate\Support\Facades\Log::info("SMS to {$request->phone}: {$message}");
            }

            return response()->json(['success' => true, 'message' => 'Verification code sent!' . ($smsDriver === 'log' ? ' (Check logs for code: ' . $code . ')' : '')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send SMS. Please try again.'], 500);
        }
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if ($user->phone_verification_code !== $request->code) {
            return response()->json(['success' => false, 'message' => 'Invalid verification code.'], 422);
        }

        $user->update([
            'phone_verified' => 1,
            'phone_verified_at' => now(),
            'phone_verification_code' => null,
        ]);

        return response()->json(['success' => true, 'message' => 'Phone number verified successfully!']);
    }

    // ===== Follow / Unfollow Company =====

    public function followCompany(BiraConsultantCompany $company)
    {
        if (!Auth::check()) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Please log in to follow companies.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please log in to follow companies.');
        }

        $user = Auth::user();

        if ($user->isFollowingCompany($company->id)) {
            if (request()->wantsJson()) {
                return response()->json(['following' => true, 'message' => 'You are already following this company.']);
            }
            return back()->with('info', 'You are already following this company.');
        }

        $user->followCompany($company);

        // Send notification to user confirming they're now following
        $user->notify(new CompanyFollowedNotification($company));

        if (request()->wantsJson()) {
            return response()->json([
                'following' => true,
                'message' => 'You are now following ' . $company->name . '!',
                'follower_count' => $company->fresh()->followerCount(),
            ]);
        }

        return back()->with('success', 'You are now following ' . $company->name . '! You will receive updates when they post new jobs.');
    }

    public function unfollowCompany(BiraConsultantCompany $company)
    {
        if (!Auth::check()) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Please log in.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please log in.');
        }

        $user = Auth::user();

        if (!$user->isFollowingCompany($company->id)) {
            if (request()->wantsJson()) {
                return response()->json(['following' => false, 'message' => 'You are not following this company.']);
            }
            return back()->with('info', 'You are not following this company.');
        }

        $user->unfollowCompany($company->id);

        if (request()->wantsJson()) {
            return response()->json([
                'following' => false,
                'message' => 'You have unfollowed ' . $company->name . '.',
                'follower_count' => $company->fresh()->followerCount(),
            ]);
        }

        return back()->with('success', 'You have unfollowed ' . $company->name . '.');
    }

    // ===== Notifications =====

    public function notifications()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(20);
        $unreadCount = $user->unreadNotifications()->count();

        return view('user.notifications', compact('notifications', 'unreadCount'));
    }

    public function markNotificationRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    // ===== Notification Preferences =====

    public function notificationPreferences()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user();
        $preferences = $user->notificationPreferences();

        return view('user.notification-preferences', compact('user', 'preferences'));
    }

    public function updateNotificationPreferences(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $request->validate([
            'notify_email' => 'boolean',
            'notify_followed_company_jobs' => 'boolean',
        ]);

        Auth::user()->update([
            'notify_email' => $request->boolean('notify_email', false),
            'notify_followed_company_jobs' => $request->boolean('notify_followed_company_jobs', false),
        ]);

        return redirect()->route('user.notifications.preferences')->with('success', 'Notification preferences updated!');
    }

    // --- Auth modal API endpoints ---

    public function apiCountries()
    {
        $countries = \App\Models\BiraConsultantCountry::orderBy('name')->get(['id', 'name']);
        return response()->json($countries);
    }

    public function checkUsername(Request $request)
    {
        $exists = \App\Models\BiraConsultantUser::where('username', $request->query('username'))->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkEmail(Request $request)
    {
        $exists = \App\Models\BiraConsultantUser::where('email', $request->query('email'))->exists();
        return response()->json(['exists' => $exists]);
    }
}

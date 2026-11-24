<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantBlog;
use App\Models\BiraConsultantCountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicApiController extends Controller
{
    /**
     * List jobs with filters.
     */
    public function jobs(Request $request)
    {
        $query = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active();

        // Filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('company', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->filled('type')) {
            $query->where('job_type_id', $request->input('type'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        if ($request->filled('experience_level')) {
            $query->where('experience_level_id', $request->input('experience_level'));
        }

        $perPage = $request->input('per_page', 15);
        $jobs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $jobs,
        ]);
    }

    /**
     * Single job detail by slug.
     */
    public function jobDetail($slug)
    {
        $job = BiraConsultantJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->active()
            ->where('slug', $slug)
            ->first();

        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Job not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $job,
        ]);
    }

    /**
     * Search jobs (quick search).
     */
    public function searchJobs(Request $request)
    {
        $search = $request->input('q', '');
        $jobs = BiraConsultantJob::with(['company'])
            ->active()
            ->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'slug', 'title', 'location', 'salary']);

        return response()->json([
            'success' => true,
            'data' => $jobs,
        ]);
    }

    /**
     * List companies.
     */
    public function companies(Request $request)
    {
        $query = BiraConsultantCompany::withCount(['jobs' => function ($q) {
                $q->where('status', 1)->where('approval_status', 'approved');
            }])
            ->where('is_approved', 1);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->input('industry'));
        }

        $perPage = $request->input('per_page', 15);
        $companies = $query->orderBy('name', 'asc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $companies,
        ]);
    }

    /**
     * Single company detail by slug.
     */
    public function companyDetail($slug)
    {
        $company = BiraConsultantCompany::with(['jobs' => function ($q) {
                $q->where('status', 1)->where('approval_status', 'approved')->latest();
            }])
            ->where('slug', $slug)
            ->where('is_approved', 1)
            ->first();

        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Company not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $company,
        ]);
    }

    /**
     * List published blogs.
     */
    public function blogs(Request $request)
    {
        $query = BiraConsultantBlog::with(['author', 'category'])
            ->where('status', 'published');

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $perPage = $request->input('per_page', 10);
        $blogs = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $blogs,
        ]);
    }

    /**
     * Single blog detail.
     */
    public function blogDetail($slug)
    {
        $blog = BiraConsultantBlog::with(['author', 'category'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog,
        ]);
    }

    /**
     * Home page jobs.
     */
    public function homeJobs()
    {
        $jobs = BiraConsultantJob::with(['company', 'category', 'jobType'])
            ->active()
            ->latest()
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jobs,
        ]);
    }

    /**
     * Home page companies.
     */
    public function homeCompanies()
    {
        $companies = BiraConsultantCompany::withCount(['jobs' => function ($q) {
                $q->where('status', 1)->where('approval_status', 'approved');
            }])
            ->where('is_approved', 1)
            ->orderBy('name', 'asc')
            ->limit(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $companies,
        ]);
    }

    /**
     * Featured jobs.
     */
    public function featuredJobs()
    {
        $jobs = BiraConsultantJob::with(['company', 'category'])
            ->active()
            ->latest()
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $jobs,
        ]);
    }

    /**
     * Global search.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $jobs = BiraConsultantJob::with(['company'])
            ->active()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get(['id', 'slug', 'title', 'location', 'salary']);

        $companies = BiraConsultantCompany::where('is_approved', 1)
            ->where('name', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'slug', 'name', 'logo', 'industry']);

        return response()->json([
            'success' => true,
            'data' => [
                'jobs' => $jobs,
                'companies' => $companies,
            ],
        ]);
    }

    /**
     * Get all countries.
     */
    public function countries()
    {
        $countries = BiraConsultantCountry::orderBy('name', 'asc')->get(['id', 'name', 'iso', 'dial']);
        return response()->json([
            'success' => true,
            'data' => $countries,
        ]);
    }

    /**
     * Meta data for filters and forms: categories, job types,
     * experience levels, countries and application statuses.
     */
    public function meta()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'categories'        => \App\Models\BiraConsultantJobCategory::withCount('jobs')->orderBy('name')->get(['id', 'name', 'slug']),
                'job_types'         => \App\Models\BiraConsultantJobType::orderBy('name')->get(['id', 'name']),
                'experience_levels' => \App\Models\BiraConsultantJobExperienceLevel::orderBy('name')->get(['id', 'name']),
                'countries'         => BiraConsultantCountry::orderBy('name')->get(['id', 'name', 'iso', 'dial']),
                'application_statuses' => \App\Models\BiraConsultantApplicationStatus::orderBy('id')->get(['id', 'label', 'color_class']),
                'ads' => $this->mobileAdsSettings(),
            ],
        ]);
    }

    /** Public mobile ad settings used by Android. New providers stay disabled until configured. */
    private function mobileAdsSettings(): array
    {
        $setting = \App\Models\BiraConsultantSetting::class;
        $providerValue = strtolower((string) $setting::getValue('mobile_ads_provider', ''));
        $provider = $providerValue;
        if ($providerValue === '') {
            if (filter_var($setting::getValue('google_ads_enabled', '1'), FILTER_VALIDATE_BOOLEAN)) {
                $provider = 'google';
            } elseif (filter_var($setting::getValue('ironsource_ads_enabled', '0'), FILTER_VALIDATE_BOOLEAN)) {
                $provider = 'ironsource';
            } elseif (filter_var($setting::getValue('tapjoy_ads_enabled', '0'), FILTER_VALIDATE_BOOLEAN)) {
                $provider = 'tapjoy';
            } else {
                $provider = 'none';
            }
        }
        if (!in_array($provider, ['google', 'ironsource', 'tapjoy', 'none'], true)) {
            $provider = 'none';
        }
        $testMode = filter_var($setting::getValue('google_ads_test_mode', '1'), FILTER_VALIDATE_BOOLEAN);
        $testBannerId = 'ca-app-pub-3940256099942544/9214589741';
        $testNativeId = 'ca-app-pub-3940256099942544/2247696110';
        $ironSourceAppKey = (string) $setting::getValue('ironsource_app_key', '');
        $ironSourceBannerAdUnitId = (string) $setting::getValue('ironsource_banner_ad_unit_id', '');

        return [
            'provider' => $provider,
            'enabled' => $provider !== 'none',
            'banner_enabled' => $provider === 'google'
                ? filter_var($setting::getValue('google_banner_enabled', '1'), FILTER_VALIDATE_BOOLEAN)
                : ($provider === 'ironsource' && filter_var($setting::getValue('ironsource_banner_enabled', '0'), FILTER_VALIDATE_BOOLEAN)),
            'native_enabled' => $provider === 'google' && filter_var($setting::getValue('google_native_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            'test_mode' => $testMode,
            'banner_unit_id' => $provider === 'google'
                ? ($testMode ? $testBannerId : ($setting::getValue('google_banner_ad_unit_id') ?: $testBannerId))
                : '',
            'native_unit_id' => $provider === 'google'
                ? ($testMode ? $testNativeId : ($setting::getValue('google_native_ad_unit_id') ?: $testNativeId))
                : '',
            'providers' => [
                'ironsource' => [
                    'enabled' => $provider === 'ironsource' && $ironSourceAppKey !== '',
                    'app_key' => $ironSourceAppKey,
                    'banner_enabled' => filter_var($setting::getValue('ironsource_banner_enabled', '0'), FILTER_VALIDATE_BOOLEAN),
                    'banner_ad_unit_id' => $ironSourceBannerAdUnitId,
                    'banner_placement' => $setting::getValue('ironsource_banner_placement', ''),
                ],
                'tapjoy' => [
                    'enabled' => $provider === 'tapjoy',
                    'sdk_key' => $setting::getValue('tapjoy_sdk_key', ''),
                    'offerwall_enabled' => filter_var($setting::getValue('tapjoy_offerwall_enabled', '0'), FILTER_VALIDATE_BOOLEAN),
                    'offerwall_placement' => $setting::getValue('tapjoy_offerwall_placement', ''),
                ],
            ],
        ];
    }

    /**
     * Per-user state for a job detail screen: has the current user
     * applied / saved / is following the company. Works for guests too.
     */
    public function jobStatus($slug)
    {
        $job = BiraConsultantJob::where('slug', $slug)->first();

        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Job not found'], 404);
        }

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'job_id'          => $job->id,
                'has_applied'     => $user ? $user->hasAppliedTo($job->id) : false,
                'has_saved'       => $user ? $user->hasSavedJob($job->id) : false,
                'following_company' => ($user && $job->company_id) ? $user->isFollowingCompany($job->company_id) : false,
                'can_apply'       => $user && $user->role === 0 && !$user->hasAppliedTo($job->id),
            ],
        ]);
    }
}

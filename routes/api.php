<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\UserActionApiController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\CompanyApiController;
use App\Http\Controllers\Api\Admin\AdminApplicationApiController;
use App\Http\Controllers\Api\Admin\AdminBlogApiController;
use App\Http\Controllers\Api\Admin\AdminCompanyApiController;
use App\Http\Controllers\Api\Admin\AdminDashboardApiController;
use App\Http\Controllers\Api\Admin\AdminJobApiController;
use App\Http\Controllers\Api\Admin\AdminSettingApiController;
use App\Http\Controllers\Api\Admin\AdminUserApiController;

/*
|--------------------------------------------------------------------------
| Public API Routes (no auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    // Auth API (session-based for Android app)
    Route::post('/auth/login', [ApiAuthController::class, 'login'])->name('api.v1.auth.login');
    Route::post('/auth/register', [ApiAuthController::class, 'register'])->name('api.v1.auth.register');
    Route::post('/auth/forgot-password', [ApiAuthController::class, 'forgotPassword'])->middleware('throttle:auth')->name('api.v1.auth.forgot-password');
    Route::post('/auth/reset-password', [ApiAuthController::class, 'resetPassword'])->middleware('throttle:auth')->name('api.v1.auth.reset-password');
    Route::get('/auth/options', [ApiAuthController::class, 'options'])->name('api.v1.auth.options');
    Route::post('/auth/google/exchange', [ApiAuthController::class, 'googleExchange'])->name('api.v1.auth.google.exchange');
    Route::post('/auth/logout', [ApiAuthController::class, 'logout'])->name('api.v1.auth.logout');
    Route::get('/auth/me', [ApiAuthController::class, 'me'])->name('api.v1.auth.me');

    // Jobs
    Route::get('/jobs', [PublicApiController::class, 'jobs'])->name('api.v1.jobs.index');
    Route::get('/jobs/{slug}', [PublicApiController::class, 'jobDetail'])->name('api.v1.jobs.show');
    Route::get('/jobs-search', [PublicApiController::class, 'searchJobs'])->name('api.v1.jobs.search');

    // Companies
    Route::get('/companies', [PublicApiController::class, 'companies'])->name('api.v1.companies.index');
    Route::get('/companies/{slug}', [PublicApiController::class, 'companyDetail'])->name('api.v1.companies.show');

    // Blogs
    Route::get('/blogs', [PublicApiController::class, 'blogs'])->name('api.v1.blogs.index');
    Route::get('/blogs/{slug}', [PublicApiController::class, 'blogDetail'])->name('api.v1.blogs.show');

    // Home data
    Route::get('/home/jobs', [PublicApiController::class, 'homeJobs'])->name('api.v1.home.jobs');
    Route::get('/home/companies', [PublicApiController::class, 'homeCompanies'])->name('api.v1.home.companies');
    Route::get('/home/featured-jobs', [PublicApiController::class, 'featuredJobs'])->name('api.v1.home.featured-jobs');

    // Search
    Route::get('/search', [PublicApiController::class, 'search'])->name('api.v1.search');

    // Countries
    Route::get('/countries', [PublicApiController::class, 'countries'])->name('api.v1.countries');

    // Meta (filters + forms) and per-user job state
    Route::get('/meta', [PublicApiController::class, 'meta'])->name('api.v1.meta');
    Route::get('/jobs/{slug}/status', [PublicApiController::class, 'jobStatus'])->name('api.v1.jobs.status');

    /*
    |--------------------------------------------------------------------------
    | User API Routes (auth:web + role:0)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:web', 'role:0'])->prefix('user')->group(function () {
        Route::get('/dashboard', [UserApiController::class, 'dashboard'])->name('api.v1.user.dashboard');
        Route::get('/profile', [UserApiController::class, 'profile'])->name('api.v1.user.profile');
        Route::get('/applications', [UserApiController::class, 'applications'])->name('api.v1.user.applications');

        // Skills
        Route::get('/skills', [UserApiController::class, 'skills'])->name('api.v1.user.skills');
        Route::post('/skills', [UserApiController::class, 'addSkill'])->name('api.v1.user.skills.add');
        Route::delete('/skills/{id}', [UserApiController::class, 'removeSkill'])->name('api.v1.user.skills.remove');

        // Work Experience
        Route::get('/work-experiences', [UserApiController::class, 'workExperiences'])->name('api.v1.user.work-experiences');
        Route::post('/work-experiences', [UserApiController::class, 'addWorkExperience'])->name('api.v1.user.work-experiences.add');
        Route::delete('/work-experiences/{id}', [UserApiController::class, 'deleteWorkExperience'])->name('api.v1.user.work-experiences.delete');

        // Followed Companies
        Route::get('/followed-companies', [UserApiController::class, 'followedCompanies'])->name('api.v1.user.followed-companies');
        Route::post('/companies/{company}/follow', [UserActionApiController::class, 'follow'])->name('api.v1.user.companies.follow');
        Route::delete('/companies/{company}/unfollow', [UserActionApiController::class, 'unfollow'])->name('api.v1.user.companies.unfollow');

        // Apply / withdraw / save
        Route::post('/jobs/{job}/apply', [UserActionApiController::class, 'apply'])->name('api.v1.user.jobs.apply');
        Route::post('/jobs/{job}/save', [UserActionApiController::class, 'toggleSave'])->name('api.v1.user.jobs.save');
        Route::delete('/applications/{id}', [UserActionApiController::class, 'withdraw'])->name('api.v1.user.applications.withdraw');
        Route::get('/saved-jobs', [UserActionApiController::class, 'savedJobs'])->name('api.v1.user.saved-jobs');

        // Notifications
        Route::get('/notifications', [UserActionApiController::class, 'notifications'])->name('api.v1.user.notifications');
        Route::post('/notifications/read-all', [UserActionApiController::class, 'markAllNotificationsRead'])->name('api.v1.user.notifications.read-all');
        Route::post('/notifications/{id}/read', [UserActionApiController::class, 'markNotificationRead'])->name('api.v1.user.notifications.read');

        // Profile & password
        Route::post('/profile', [UserActionApiController::class, 'updateProfile'])->name('api.v1.user.profile.update');
        Route::post('/identity', [UserActionApiController::class, 'uploadIdentity'])->name('api.v1.user.identity.upload');
        Route::post('/change-password', [UserActionApiController::class, 'changePassword'])->name('api.v1.user.change-password');
    });

    // Company endpoints mirror the authenticated company dashboard actions.
    Route::middleware(['auth:web', 'company.access'])->prefix('company')->name('api.v1.company.')->group(function () {
        Route::get('/dashboard', [CompanyApiController::class, 'dashboard'])->name('dashboard');
        Route::get('/applications', [CompanyApiController::class, 'applications'])->name('applications.index');
        Route::get('/jobs/{job}/applications', [CompanyApiController::class, 'jobApplications'])->name('jobs.applications');
        Route::patch('/application/{application}/stage', [CompanyApiController::class, 'updateStage'])->name('applications.stage');
        Route::patch('/applications/{application}/stage', [CompanyApiController::class, 'updateStage'])->name('applications.stage.rest');
        Route::get('/verification', [CompanyApiController::class, 'verification'])->name('verification');
        Route::get('/settings', [CompanyApiController::class, 'settings'])->name('settings');
        Route::post('/settings', [CompanyApiController::class, 'updateSettings'])->name('settings.update');
        Route::get('/jobs', [CompanyApiController::class, 'jobs'])->name('jobs.index');
        Route::post('/jobs', [CompanyApiController::class, 'storeJob'])->name('jobs.store');
        Route::get('/jobs/{job}', [CompanyApiController::class, 'showJob'])->name('jobs.show');
        Route::put('/jobs/{job}', [CompanyApiController::class, 'updateJob'])->name('jobs.update');
        Route::patch('/jobs/{job}', [CompanyApiController::class, 'updateJob'])->name('jobs.patch');
        Route::patch('/jobs/{job}/toggle', [CompanyApiController::class, 'toggleJobStatus'])->name('jobs.toggle');
        Route::delete('/jobs/{job}', [CompanyApiController::class, 'deleteJob'])->name('jobs.delete');
    });

    // Admin API endpoints cover the same records managed in the website panel.
    Route::middleware(['auth:web', 'role:1'])->prefix('admin')->name('api.v1.admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardApiController::class, 'index'])->name('dashboard');

        Route::get('/jobs', [AdminJobApiController::class, 'index'])->name('jobs.index');
        Route::post('/jobs', [AdminJobApiController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{slug}', [AdminJobApiController::class, 'show'])->name('jobs.show');
        Route::put('/jobs/{slug}', [AdminJobApiController::class, 'update'])->name('jobs.update');
        Route::patch('/jobs/{slug}', [AdminJobApiController::class, 'update'])->name('jobs.patch');
        Route::delete('/jobs/{slug}', [AdminJobApiController::class, 'destroy'])->name('jobs.delete');
        Route::patch('/jobs/{slug}/approve', [AdminJobApiController::class, 'approve'])->name('jobs.approve');
        Route::patch('/jobs/{slug}/reject', [AdminJobApiController::class, 'reject'])->name('jobs.reject');

        Route::get('/companies', [AdminCompanyApiController::class, 'index'])->name('companies.index');
        Route::post('/companies', [AdminCompanyApiController::class, 'store'])->name('companies.store');
        Route::get('/companies/{slug}', [AdminCompanyApiController::class, 'show'])->name('companies.show');
        Route::put('/companies/{slug}', [AdminCompanyApiController::class, 'update'])->name('companies.update');
        Route::patch('/companies/{slug}', [AdminCompanyApiController::class, 'update'])->name('companies.patch');
        Route::delete('/companies/{slug}', [AdminCompanyApiController::class, 'destroy'])->name('companies.delete');
        Route::patch('/companies/{slug}/approve', [AdminCompanyApiController::class, 'approveVerification'])->name('companies.approve');
        Route::patch('/companies/{slug}/reject', [AdminCompanyApiController::class, 'rejectVerification'])->name('companies.reject');

        Route::get('/applications', [AdminApplicationApiController::class, 'index'])->name('applications.index');
        Route::get('/applications/{id}', [AdminApplicationApiController::class, 'show'])->name('applications.show');
        Route::patch('/applications/{id}', [AdminApplicationApiController::class, 'update'])->name('applications.update');
        Route::delete('/applications/{id}', [AdminApplicationApiController::class, 'destroy'])->name('applications.delete');

        Route::get('/users', [AdminUserApiController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserApiController::class, 'store'])->name('users.store');
        Route::get('/users/{slug}', [AdminUserApiController::class, 'show'])->name('users.show');
        Route::put('/users/{slug}', [AdminUserApiController::class, 'update'])->name('users.update');
        Route::patch('/users/{slug}', [AdminUserApiController::class, 'update'])->name('users.patch');
        Route::delete('/users/{slug}', [AdminUserApiController::class, 'destroy'])->name('users.delete');
        Route::delete('/users/{id}/profile-picture', [AdminUserApiController::class, 'deleteProfilePic'])->name('users.profile-picture.delete');
        Route::delete('/users/{id}/cover-picture', [AdminUserApiController::class, 'deleteCoverPic'])->name('users.cover-picture.delete');
        Route::post('/users/{user}/skills', [AdminUserApiController::class, 'addSkill'])->name('users.skills.add');
        Route::delete('/users/{user}/skills/{skill}', [AdminUserApiController::class, 'removeSkill'])->name('users.skills.delete');
        Route::get('/identity-verification', [AdminUserApiController::class, 'verification'])->name('identity-verification.index');
        Route::patch('/users/{user}/identity/approve', [AdminUserApiController::class, 'approveIdentity'])->name('users.identity.approve');
        Route::patch('/users/{user}/identity/reject', [AdminUserApiController::class, 'rejectIdentity'])->name('users.identity.reject');

        Route::get('/blogs', [AdminBlogApiController::class, 'index'])->name('blogs.index');
        Route::post('/blogs', [AdminBlogApiController::class, 'store'])->name('blogs.store');
        Route::get('/blogs/{slug}', [AdminBlogApiController::class, 'show'])->name('blogs.show');
        Route::put('/blogs/{slug}', [AdminBlogApiController::class, 'update'])->name('blogs.update');
        Route::patch('/blogs/{slug}', [AdminBlogApiController::class, 'update'])->name('blogs.patch');
        Route::delete('/blogs/{slug}', [AdminBlogApiController::class, 'destroy'])->name('blogs.delete');

        Route::get('/settings', [AdminSettingApiController::class, 'index'])->name('settings.index');
        Route::get('/settings/{key}', [AdminSettingApiController::class, 'show'])->name('settings.show');
        Route::put('/settings', [AdminSettingApiController::class, 'update'])->name('settings.update');
        Route::patch('/settings', [AdminSettingApiController::class, 'update'])->name('settings.patch');
    });

});

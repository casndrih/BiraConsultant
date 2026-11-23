<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\CompanyDashboardController;

Route::get('/healthz', fn () => response()->json(['status' => 'ok']))->name('healthz');

// Serve public profile photos directly from the public disk. Some packaged
// Oreshnik workspaces cannot maintain Laravel's public/storage symlink.
Route::get('/storage/profiles/{path}', function (string $path) {
    $profileRoot = realpath(storage_path('app/public/profiles'));
    $filePath = realpath(storage_path('app/public/profiles/' . $path));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

    if (!$profileRoot || !$filePath || !is_file($filePath) ||
        !str_starts_with($filePath, $profileRoot . DIRECTORY_SEPARATOR) ||
        !in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), $allowedExtensions, true)) {
        abort(404);
    }

    return response()->file($filePath, [
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->where('path', '.*');

// Auth modal API endpoints
Route::get('/api/countries', [FrontendController::class, 'apiCountries'])->middleware('throttle:lookup');
Route::get('/check-username', [FrontendController::class, 'checkUsername'])->middleware('throttle:lookup');
Route::get('/check-email', [FrontendController::class, 'checkEmail'])->middleware('throttle:lookup');

// Jobs AJAX listing
Route::get('/jobs/list', [FrontendController::class, 'jobsList'])->name('jobs.list');

// Companies AJAX listing
Route::get('/companies/list', [FrontendController::class, 'companiesList'])->name('companies.list');

// Company Applications AJAX listing
Route::get('/company/applications/list', [CompanyDashboardController::class, 'applicationsList'])->name('company.applications.list');

// Frontend routes
Route::controller(FrontendController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/jobs', 'jobs')->name('jobs');
    Route::get('/jobs/{slug}', 'jobDetailBySlug')->name('job.detail');
    Route::get('/job', 'jobDetailByQuery')->name('job.detail.query');
    Route::get('/companies', 'companies')->name('companies');
    Route::get('/companies/{slug}', 'companyDetailBySlug')->name('company.detail');
    Route::get('/company', 'companyDetailByQuery')->name('company.detail.query');
    Route::get('/search', 'search')->name('search');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/blog/{slug}', 'blogDetail')->name('blog.detail');
    Route::get('/about', 'about')->name('about');
    Route::get('/advertising', 'advertising')->name('advertising');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'submitContact')->name('contact.submit')->middleware('recaptcha');
    Route::get('/latest-jobs', [FrontendController::class, 'latestJobsPartial'])->name('latest.jobs');
    Route::get('/featured-jobs', [FrontendController::class, 'featuredJobsPartial'])->name('featured.jobs');
    Route::get('/home-companies', [FrontendController::class, 'homeCompaniesPartial'])->name('home.companies');
    Route::get('/privacy', 'privacy')->name('privacy');
    Route::get('/terms', 'terms')->name('terms');
});

// Auth routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/signin', 'signIn')->name('signIn')->middleware(['recaptcha', 'throttle:auth']);
    Route::get('/signout', 'signOut')->name('signOut');
    Route::post('/forgotpassword', 'forgotPassword')->name('forgot-password')->middleware(['recaptcha', 'throttle:auth']);
});

Route::controller(GoogleAuthController::class)->prefix('auth/google')->group(function () {
    Route::get('/redirect', 'redirect')->name('auth.google.redirect');
    Route::get('/callback', 'callback')->name('auth.google.callback');
});

// Signup routes
Route::controller(RegistrationController::class)->group(function () {
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'reset')->name('password.update')->middleware('throttle:auth');
    Route::post('/signup', 'signUp')->name('signUp')->middleware(['recaptcha', 'throttle:auth']);
    Route::post('/check-email', 'checkEmail')->middleware('throttle:lookup');
    Route::post('/check-username', 'checkUsername')->middleware('throttle:lookup');
    Route::get('/verify/{token}', 'verifyEmail')->name('email.verify');
});

// User routes
Route::prefix('user')->group(function () {
    Route::group(['middleware' => ['auth:web', 'role:0']], function () {
        Route::get('/dashboard', [FrontendController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/profile', [FrontendController::class, 'profile'])->name('user.profile');
        Route::get('/applications', [FrontendController::class, 'applications'])->name('user.applications');
        Route::post('/profile/update', [FrontendController::class, 'updateProfile'])->name('user.profile.update');
        Route::post('/application/{job_id}', [FrontendController::class, 'applyJob'])->name('user.apply')->middleware('recaptcha');
        Route::delete('/application/{id}', [FrontendController::class, 'withdrawApplication'])->name('user.application.withdraw');
        Route::post('/job/{job_id}/toggle-save', [FrontendController::class, 'toggleSaveJob'])->name('user.job.toggle-save');

        // Work Experience
        Route::post('/work-experience', [FrontendController::class, 'addWorkExperience'])->name('user.work-experience.add');
        Route::delete('/work-experience/{id}', [FrontendController::class, 'deleteWorkExperience'])->name('user.work-experience.delete');

        // Skills
        Route::post('/skill/add', [FrontendController::class, 'addSkill'])->name('user.skill.add');
        Route::post('/skill/remove', [FrontendController::class, 'removeSkill'])->name('user.skill.remove');

        // Password
        Route::post('/change-password', [FrontendController::class, 'changePassword'])->name('user.change-password');

        // Identity Verification
        Route::post('/identity/upload', [FrontendController::class, 'uploadIdentity'])->name('user.identity.upload');

        // Follow/Unfollow Company
        Route::post('/company/{company}/follow', [FrontendController::class, 'followCompany'])->name('user.company.follow');
        Route::delete('/company/{company}/unfollow', [FrontendController::class, 'unfollowCompany'])->name('user.company.unfollow');

        // Notification Preferences
        Route::get('/notifications/preferences', [FrontendController::class, 'notificationPreferences'])->name('user.notifications.preferences');
        Route::post('/notifications/preferences', [FrontendController::class, 'updateNotificationPreferences'])->name('user.notifications.preferences.update');
        Route::get('/notifications', [FrontendController::class, 'notifications'])->name('user.notifications');
        Route::post('/notifications/{id}/read', [FrontendController::class, 'markNotificationRead'])->name('user.notifications.read');
        Route::post('/notifications/read-all', [FrontendController::class, 'markAllNotificationsRead'])->name('user.notifications.read-all');

        // Phone Verification
        Route::post('/phone/send-code', [FrontendController::class, 'sendPhoneCode'])->name('user.phone.send-code');
        Route::post('/phone/verify', [FrontendController::class, 'verifyPhone'])->name('user.phone.verify');
    });
});

// Company routes
Route::prefix('company')->group(function () {
    Route::get('/register', [FrontendController::class, 'showCompanyRegister'])->name('company.register');
    Route::post('/register', [FrontendController::class, 'registerCompany'])->name('company.store');
});

// Company Dashboard routes (authenticated company owners)
Route::prefix('company')->group(function () {
    Route::group(['middleware' => ['auth:web', 'company.access']], function () {
        Route::get('/dashboard', [App\Http\Controllers\CompanyDashboardController::class, 'index'])->name('company.dashboard');
        Route::get('/applications', [App\Http\Controllers\CompanyDashboardController::class, 'applications'])->name('company.applications');
        Route::get('/applications/{job}', [App\Http\Controllers\CompanyDashboardController::class, 'jobApplications'])->name('company.job-applications');
        Route::patch('/application/{application}/stage', [App\Http\Controllers\CompanyDashboardController::class, 'updateStage'])->name('company.application.stage');
        Route::get('/application/{application}/download/{type}', [App\Http\Controllers\CompanyDashboardController::class, 'downloadFile'])->name('company.application.download');
        Route::get('/verification', [App\Http\Controllers\CompanyDashboardController::class, 'verification'])->name('company.verification');
        Route::post('/verification/upload', [App\Http\Controllers\CompanyDashboardController::class, 'uploadVerification'])->name('company.verification.upload');

        // Company Profile Settings
        Route::get('/settings', [App\Http\Controllers\CompanyDashboardController::class, 'settings'])->name('company.settings');
        Route::post('/settings', [App\Http\Controllers\CompanyDashboardController::class, 'updateSettings'])->name('company.settings.update');

        // Company Job Management
        Route::get('/jobs/import/template', [App\Http\Controllers\CompanyDashboardController::class, 'downloadBulkJobTemplate'])->name('company.jobs.import.template');
        Route::get('/jobs/import', [App\Http\Controllers\CompanyDashboardController::class, 'showBulkJobImport'])->name('company.jobs.import');
        Route::post('/jobs/import', [App\Http\Controllers\CompanyDashboardController::class, 'importJobs'])->name('company.jobs.import.store');
        Route::get('/jobs/create', [App\Http\Controllers\CompanyDashboardController::class, 'createJob'])->name('company.jobs.create');
        Route::post('/jobs', [App\Http\Controllers\CompanyDashboardController::class, 'storeJob'])->name('company.jobs.store');
        Route::get('/jobs/{job}/edit', [App\Http\Controllers\CompanyDashboardController::class, 'editJob'])->name('company.jobs.edit');
        Route::put('/jobs/{job}', [App\Http\Controllers\CompanyDashboardController::class, 'updateJob'])->name('company.jobs.update');
        Route::patch('/jobs/{job}/toggle', [App\Http\Controllers\CompanyDashboardController::class, 'toggleJobStatus'])->name('company.jobs.toggle');
        Route::delete('/jobs/{job}', [App\Http\Controllers\CompanyDashboardController::class, 'deleteJob'])->name('company.jobs.delete');
    });
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['auth:web', 'role:1']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin-dashboard');

        Route::controller(JobController::class)->group(function () {
            Route::get('/jobs', 'index')->name('admin.jobs.index');
            Route::get('/job/search', 'query')->name('admin.job.search');
            Route::post('/job/delete', 'delete')->name('admin.job.delete');
            Route::post('/job/update', 'update')->name('admin.job.update');
            Route::post('/job/store', 'store')->name('admin.job.create');
            Route::get('/job/edit/{slug}', 'edit')->name('admin.job.edit');
        });

        Route::controller(CompanyController::class)->group(function () {
            Route::get('/companies', 'index')->name('admin.companies.index');
            Route::get('/company/search', 'query')->name('admin.company.search');
            Route::get('/company/delete/{slug}', 'delete')->name('admin.company.delete');
            Route::post('/company/update', 'update')->name('admin.company.update');
            Route::post('/company/store', 'store')->name('admin.company.create');
            Route::get('/company/edit/{slug}', 'edit')->name('admin.company.edit');
        });

        Route::controller(BlogController::class)->group(function () {
            Route::get('/blogs', 'index')->name('admin.blogs.index');
            Route::get('/blog/search', 'query')->name('admin.blog.search');
            Route::get('/blog/delete/{slug}', 'delete')->name('admin.blog.delete');
            Route::post('/blog/update', 'update')->name('admin.blog.update');
            Route::post('/blog/store', 'store')->name('admin.blog.create');
            Route::get('/blog/edit/{slug}', 'edit')->name('admin.blog.edit');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index')->name('admin.users.index');
            Route::get('/user/search', 'query')->name('admin.user.search');
            Route::get('/user/delete/{slug}', 'delete')->name('admin.user.delete');
            Route::post('/user/update', 'update')->name('admin.user.update');
            Route::post('/user/store', 'store')->name('admin.user.create');
            Route::get('/user/edit/{slug}', 'edit')->name('admin.user.edit');
            Route::delete('/user/delete/propic/{id}', 'deleteProfilePic')->name('admin.user.propic.delete');
            Route::delete('/user/delete/coverpic/{id}', 'deleteCoverPic')->name('admin.user.coverpic.delete');
            Route::post('/user/skill/add', 'addSkill')->name('admin.user.skill.add');
            Route::post('/user/skill/remove', 'removeSkill')->name('admin.user.skill.remove');
        });

        Route::controller(ApplicationController::class)->group(function () {
            Route::get('/applications', 'index')->name('admin.applications.index');
            Route::get('/application/search', 'query')->name('admin.application.search');
            Route::get('/application/delete/{id}', 'delete')->name('admin.application.delete');
            Route::post('/application/update', 'update')->name('admin.application.update');
            Route::get('/application/edit/{id}', 'edit')->name('admin.application.edit');

            Route::get('/application/cover-letter/{filename}', 'downloadCoverLetter')->where('filename', '.*')->name('admin.cover_letter.download');
        });

        Route::controller(SettingController::class)->group(function () {
            Route::get('/settings', 'index')->name('admin.settings.index');
            Route::get('/setting/search', 'query')->name('admin.setting.search');
            Route::post('/setting/update', 'update')->name('admin.setting.update');
        });

        // Verification Review Routes
        Route::get('/users/verification', [UserController::class, 'verification'])->name('admin.users.verification');
        Route::patch('/users/{user}/verification/approve', [UserController::class, 'approveIdentity'])->name('admin.users.verification.approve');
        Route::patch('/users/{user}/verification/reject', [UserController::class, 'rejectIdentity'])->name('admin.users.verification.reject');
        Route::get('/companies/verification', [CompanyController::class, 'verification'])->name('admin.companies.verification');
        Route::patch('/companies/{company}/verification/approve', [CompanyController::class, 'approveVerification'])->name('admin.companies.verification.approve');
        Route::patch('/companies/{company}/verification/reject', [CompanyController::class, 'rejectVerification'])->name('admin.companies.verification.reject');

        // Job Approval Routes
        Route::patch('/jobs/{job}/approve', [JobController::class, 'approve'])->name('admin.jobs.approve');
        Route::patch('/jobs/{job}/reject', [JobController::class, 'reject'])->name('admin.jobs.reject');
    });
});

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\UploadLimitHelper;
use App\Models\QJobsApplication;
use App\Models\QJobsCompany;
use App\Models\QJobsJob;
use App\Models\QJobsUserWorkExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * User action API endpoints for the Android app:
 * apply / withdraw / save / follow / notifications / profile / password.
 */
class UserActionApiController extends Controller
{
    /* ========================= Applications ========================= */

    /** POST /api/v1/user/jobs/{job}/apply */
    public function apply(Request $request, $job)
    {
        $user = Auth::user();
        $job = QJobsJob::active()->find($job);

        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Job not found or not active'], 404);
        }

        if ($user->hasAppliedTo($job->id)) {
            return response()->json(['success' => false, 'message' => 'You have already applied for this job.'], 409);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'cover_letter'      => 'nullable|string|max:5000',
            'cv'                => 'nullable|file|mimes:pdf,doc,docx|max:' . UploadLimitHelper::maxRule('application_files'),
            'resume'            => 'nullable|file|mimes:pdf,doc,docx|max:' . UploadLimitHelper::maxRule('application_files'),
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:' . UploadLimitHelper::maxRule('application_files'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $coverLetter = trim((string) $request->input('cover_letter', ''));
        if (mb_strlen($coverLetter) < 50 && !$request->hasFile('cover_letter_file')) {
            return response()->json([
                'success' => false,
                'message' => 'Write at least 50 characters or attach a cover letter document.',
                'errors' => ['cover_letter' => ['Write at least 50 characters or attach a cover letter document.']],
            ], 422);
        }

        $data = [
            'user_id'      => $user->id,
            'job_id'       => $job->id,
            'status_id'    => 1, // Received
            'cover_letter' => $coverLetter ?: 'Cover letter attached.',
        ];

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('applications/' . $user->id . '/cv', 'public');
        }
        if ($request->hasFile('resume')) {
            $data['resume_path'] = $request->file('resume')->store('applications/' . $user->id . '/resume', 'public');
        }
        if ($request->hasFile('cover_letter_file')) {
            $data['cover_letter_path'] = $request->file('cover_letter_file')->store('applications/' . $user->id . '/cover_letter', 'public');
        }

        $application = QJobsApplication::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully! The company will review your profile.',
            'data'    => $application->load('job.company'),
        ], 201);
    }

    /** DELETE /api/v1/user/applications/{id} — withdraw a pending application */
    public function withdraw($id)
    {
        $application = QJobsApplication::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status_id', 1) // only pending applications can be withdrawn
            ->first();

        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found or can no longer be withdrawn.',
            ], 404);
        }

        $application->delete();

        return response()->json(['success' => true, 'message' => 'Application withdrawn.']);
    }

    /* ========================= Saved jobs ========================= */

    /** POST /api/v1/user/jobs/{job}/save — toggle saved state */
    public function toggleSave($job)
    {
        $job = QJobsJob::find($job);
        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Job not found'], 404);
        }

        $user = Auth::user();

        if ($user->hasSavedJob($job->id)) {
            $user->savedJobs()->detach($job->id);
            return response()->json(['success' => true, 'saved' => false, 'message' => 'Job removed from saved list']);
        }

        $user->savedJobs()->attach($job->id);
        return response()->json(['success' => true, 'saved' => true, 'message' => 'Job saved successfully']);
    }

    /** GET /api/v1/user/saved-jobs */
    public function savedJobs()
    {
        $jobs = Auth::user()->savedJobs()
            ->with(['company', 'category', 'jobType'])
            ->latest('qjobs_saved_jobs.created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $jobs]);
    }

    /* ========================= Followed companies ========================= */

    /** POST /api/v1/user/companies/{company}/follow */
    public function follow($company)
    {
        $company = QJobsCompany::find($company);
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Company not found'], 404);
        }

        $user = Auth::user();

        if ($user->isFollowingCompany($company->id)) {
            return response()->json(['success' => true, 'following' => true, 'message' => 'You are already following this company.']);
        }

        $user->followCompany($company);
        $user->notify(new \App\Notifications\CompanyFollowedNotification($company));

        return response()->json([
            'success'       => true,
            'following'     => true,
            'message'       => 'You are now following ' . $company->name . '!',
            'follower_count' => $company->fresh()->followerCount(),
        ]);
    }

    /** DELETE /api/v1/user/companies/{company}/unfollow */
    public function unfollow($company)
    {
        $company = QJobsCompany::find($company);
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Company not found'], 404);
        }

        $user = Auth::user();

        if (!$user->isFollowingCompany($company->id)) {
            return response()->json(['success' => true, 'following' => false, 'message' => 'You are not following this company.']);
        }

        $user->unfollowCompany($company->id);

        return response()->json([
            'success'       => true,
            'following'     => false,
            'message'       => 'You have unfollowed ' . $company->name . '.',
            'follower_count' => $company->fresh()->followerCount(),
        ]);
    }

    /* ========================= Notifications ========================= */

    /** GET /api/v1/user/notifications */
    public function notifications(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(20);
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'data'    => array_merge($notifications->toArray(), ['unread_count' => $unreadCount]),
        ]);
    }

    /** POST /api/v1/user/notifications/{id}/read */
    public function markNotificationRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    /** POST /api/v1/user/notifications/read-all */
    public function markAllNotificationsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }

    /* ========================= Profile ========================= */

    /** POST /api/v1/user/profile — update profile (JSON or multipart with image) */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:qjobs_users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
            'bio'        => 'nullable|string|max:1000',
            'website'    => 'nullable|url|max:255',
            'country_id' => 'nullable|exists:qjobs_countries,id',
            'state'      => 'nullable|string|max:255',
            'birthday'   => 'nullable|date',
            'gender'     => 'nullable|string|max:15',
            'image'      => 'nullable|image|max:' . UploadLimitHelper::maxRule('profile_image'),
            'cover'      => 'nullable|image|max:' . UploadLimitHelper::maxRule('profile_cover_image'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $request->only(['name', 'email', 'phone', 'bio', 'website', 'country_id', 'state', 'birthday', 'gender']);

        if ($request->hasFile('image')) {
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

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'data'    => $user->fresh()->load(['country', 'skills', 'workExperiences']),
        ]);
    }

    /** POST /api/v1/user/identity — submit an ID photo or PDF for review. */
    public function uploadIdentity(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'identity_document_type' => 'required|in:nid,work_id,passport,driver_license',
            'identity_number' => 'required|string|max:50',
            'identity_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:' . UploadLimitHelper::maxRule('nid_docs'),
            'identity_document_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:' . UploadLimitHelper::maxRule('nid_docs'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        $disk = Storage::disk('public');
        foreach ([$user->nid_front_path, $user->nid_back_path] as $oldPath) {
            if ($oldPath) $disk->delete($oldPath);
        }

        $frontPath = $request->file('identity_document')->store('identity/' . $user->id, 'public');
        $backPath = $request->hasFile('identity_document_back')
            ? $request->file('identity_document_back')->store('identity/' . $user->id, 'public')
            : null;

        $user->update([
            'identity_document_type' => $request->input('identity_document_type'),
            'nid_number' => $request->input('identity_number'),
            'nid_front_path' => $frontPath,
            'nid_back_path' => $backPath,
            'identity_verified' => 0,
            'identity_rejection_reason' => null,
            'identity_verified_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Identity document submitted for review.',
            'data' => $user->fresh()->load(['country', 'skills', 'workExperiences']),
        ], 201);
    }

    /** POST /api/v1/user/change-password */
    public function changePassword(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
                'errors'  => ['current_password' => ['Current password is incorrect.']],
            ], 422);
        }

        $user->update(['password' => Hash::make($request->input('password'))]);

        return response()->json(['success' => true, 'message' => 'Password changed successfully!']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\BiraConsultantUser;
use App\Models\BiraConsultantJobSkill;
use App\Models\BiraConsultantCountry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Helpers\UploadLimitHelper;

class UserController extends Controller
{
    public function index()
    {
        $users = BiraConsultantUser::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function query(Request $request)
    {
        $users = BiraConsultantUser::query();

        if ($request->filled('search')) {
            $users->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $users->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function edit($slug)
    {
        $user = BiraConsultantUser::where('slug', $slug)->firstOrFail();
        $countries = BiraConsultantCountry::all(['id', 'name', 'iso', 'iso3', 'dial']);

        return response()->json([
               'user' => $user,
               'countries' => $countries,
               'skills' => $user->skills,
    ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_user_id' => 'required|exists:biraconsultant_users,id',
            'edit_user_name' => ['required', 'regex:/^[a-zA-Z\s]+$/u', 'max:255'],
            'edit_user_dob' => 'required|date_format:Y-m-d',
            'edit_user_email' => 'required|email|max:255',
            'edit_user_gender' => 'required|in:0,1',
            'edit_user_country_id' => 'nullable|exists:biraconsultant_countries,id',
            'edit_user_state' => 'nullable|string|max:100',
            'edit_user_phone' => 'nullable|digits_between:1,15',
            'user_role' => 'required|in:0,1,2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:' . UploadLimitHelper::maxRule('admin_user_image'),
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:' . UploadLimitHelper::maxRule('admin_user_cover'),
        ]);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            Session::flash('error', 'Please check your input: ' . implode(' ', $errorMessages));
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = BiraConsultantUser::findOrFail($request->edit_user_id);

        $user->name = $request->edit_user_name;
        $user->birthday = $request->edit_user_dob;
        $user->email = $request->edit_user_email;
        $user->gender = $request->edit_user_gender;
        $user->country_id = $request->edit_user_country_id;
        $user->state = $request->edit_user_state;
        $user->phone = $request->edit_user_phone;
        $user->role = $request->user_role;

        if ($request->hasFile('image')) {
            if ($user->image) {
                $existingImagePath = str_replace(asset('storage/'), '', $user->image);
                $existingImagePath = public_path('storage/' . $existingImagePath);

                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }

            $randomFileName = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('users/uploads/images', $randomFileName, 'public');
            $user->image = asset('storage/' . $path);
        }

        if ($request->hasFile('cover')) {
            if ($user->cover) {
                $existingCoverPath = str_replace(asset('storage/'), '', $user->cover);
                $existingCoverPath = public_path('storage/' . $existingCoverPath);
                if (file_exists($existingCoverPath)) {
                    unlink($existingCoverPath);
                }
            }

            $randomFileName = Str::random(20) . '.' . $request->file('cover')->getClientOriginalExtension();
            $path = $request->file('cover')->storeAs('users/uploads/covers', $randomFileName, 'public');
            $user->cover = asset('storage/' . $path);
        }

        $user->save();

        Session::flash('success', 'User updated successfully.');
        return Redirect::to('/admin/users');
    }

    public function delete($slug)
    {
        try {
            $user = BiraConsultantUser::where('slug', $slug)->firstOrFail();

            if ($user->image) {
                $existingImagePath = str_replace(asset('storage/'), '', $user->image);
                $existingImagePath = public_path('storage/' . $existingImagePath);
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }

            $user->delete();

            Session::flash('success', 'User and associated data deleted successfully.');
            return Redirect::to('/admin/users');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'An error occurred while deleting the user.']);
        }
    }
    
    public function deleteProfilePic($id)
    {
    $user = BiraConsultantUser::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    if ($user->image) {
        $existingImagePath = str_replace(asset('storage/'), '', $user->image);
        $existingImagePath = public_path('storage/' . $existingImagePath);

        if (file_exists($existingImagePath)) {
            unlink($existingImagePath);
        }

        $user->image = null;
        $user->save();
    }

    return response()->json(['message' => 'Profile picture deleted successfully.']);
    }

    public function deleteCoverPic($id)
    {
    $user = BiraConsultantUser::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    if ($user->cover) {
        $existingCoverPath = str_replace(asset('storage/'), '', $user->cover);
        $existingCoverPath = public_path('storage/' . $existingCoverPath);

        if (file_exists($existingCoverPath)) {
            unlink($existingCoverPath);
        }

        $user->cover = null;
        $user->save();
    }

    return response()->json(['message' => 'Cover picture deleted successfully.']);
    }
    
    public function addSkill(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'user_id' => 'required|exists:biraconsultant_users,id',
    ]);

    $skill = BiraConsultantJobSkill::firstOrCreate(['name' => $request->name]);
    $user = BiraConsultantUser::find($request->user_id);
    $user->skills()->syncWithoutDetaching([$skill->id]);

    return response()->json(['id' => $skill->id, 'name' => $skill->name]);
    }

    public function removeSkill(Request $request)
    {
    $request->validate([
        'skill_id' => 'required|exists:biraconsultant_job_skills,id',
        'user_id' => 'required|exists:biraconsultant_users,id',
    ]);

    $user = BiraConsultantUser::find($request->user_id);
    $user->skills()->detach($request->skill_id);

    return response()->json(['success' => true]);
    }

    // ===== Identity Verification Review =====

    public function verification(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        $query = BiraConsultantUser::whereNotNull('nid_front_path');

        $query->when($tab === 'pending', function ($q) {
            $q->where('identity_verified', 0);
        })->when($tab === 'verified', function ($q) {
            $q->where('identity_verified', 1);
        })->when($tab === 'rejected', function ($q) {
            $q->where('identity_verified', 2);
        });

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Counts for tabs
        $counts = [
            'pending'  => BiraConsultantUser::whereNotNull('nid_front_path')->where('identity_verified', 0)->count(),
            'verified' => BiraConsultantUser::whereNotNull('nid_front_path')->where('identity_verified', 1)->count(),
            'rejected' => BiraConsultantUser::whereNotNull('nid_front_path')->where('identity_verified', 2)->count(),
        ];

        $activeTab = $tab;
        $pendingCount = $counts['pending'];

        return view('admin.users.verification', compact('users', 'tab', 'activeTab', 'counts', 'pendingCount'));
    }

    public function approveIdentity(Request $request, BiraConsultantUser $user)
    {
        $user->update([
            'identity_verified' => 1,
            'identity_verified_at' => now(),
            'identity_rejection_reason' => null,
        ]);

        return redirect()->route('admin.users.verification')->with('success', "{$user->name}'s identity has been verified.");
    }

    public function rejectIdentity(Request $request, BiraConsultantUser $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'identity_verified' => 2,
            'identity_rejection_reason' => $request->rejection_reason,
            'identity_verified_at' => null,
        ]);

        return redirect()->route('admin.users.verification')->with('success', "{$user->name}'s identity verification has been rejected.");
    }
}

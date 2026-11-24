<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantJobSkill;
use App\Models\BiraConsultantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminUserApiController extends Controller
{
    public function index(Request $request)
    {
        $query = BiraConsultantUser::with(['country']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->paginate(20);
        return response()->json(['success' => true, 'data' => $users]);
    }

    public function show($slug)
    {
        $user = BiraConsultantUser::with(['country', 'skills', 'workExperiences'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:biraconsultant_users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:0,1,2',
        ]);

        $user = BiraConsultantUser::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['success' => true, 'data' => $user], 201);
    }

    public function update(Request $request, $slug)
    {
        $user = BiraConsultantUser::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:biraconsultant_users,email,' . $user->id,
            'role' => 'required|in:0,1,2',
        ]);

        $user->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return response()->json(['success' => true, 'data' => $user]);
    }

    public function destroy($slug)
    {
        $user = BiraConsultantUser::where('slug', $slug)->firstOrFail();
        $user->delete();
        return response()->json(['success' => true]);
    }

    public function deleteProfilePic($id)
    {
        $user = BiraConsultantUser::findOrFail($id);
        if ($user->image) {
            $path = str_replace(asset('storage/'), '', $user->image);
            $fullPath = public_path('storage/' . $path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $user->update(['image' => null]);
        }
        return response()->json(['success' => true]);
    }

    public function deleteCoverPic($id)
    {
        $user = BiraConsultantUser::findOrFail($id);
        if ($user->cover) {
            $path = str_replace(asset('storage/'), '', $user->cover);
            $fullPath = public_path('storage/' . $path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $user->update(['cover' => null]);
        }
        return response()->json(['success' => true]);
    }

    public function addSkill(Request $request, $user)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $skill = \App\Models\BiraConsultantJobSkill::firstOrCreate(['name' => $request->name]);
        $user = BiraConsultantUser::findOrFail($user);
        $user->skills()->syncWithoutDetaching([$skill->id]);
        return response()->json(['success' => true, 'data' => $skill]);
    }

    public function removeSkill($user, $skill)
    {
        $user = BiraConsultantUser::findOrFail($user);
        $user->skills()->detach($skill);
        return response()->json(['success' => true]);
    }

    public function verification(Request $request)
    {
        $tab = $request->query('tab', 'pending');
        $query = BiraConsultantUser::whereNotNull('nid_front_path');

        $query->when($tab === 'pending', fn($q) => $q->where('identity_verified', 0))
              ->when($tab === 'verified', fn($q) => $q->where('identity_verified', 1))
              ->when($tab === 'rejected', fn($q) => $q->where('identity_verified', 2));

        $users = $query->latest()->paginate(20);

        $counts = [
            'pending' => BiraConsultantUser::whereNotNull('nid_front_path')->where('identity_verified', 0)->count(),
            'verified' => BiraConsultantUser::whereNotNull('nid_front_path')->where('identity_verified', 1)->count(),
            'rejected' => BiraConsultantUser::whereNotNull('nid_front_path')->where('identity_verified', 2)->count(),
        ];

        return response()->json(['success' => true, 'data' => ['users' => $users, 'counts' => $counts]]);
    }

    public function approveIdentity($user)
    {
        $user = BiraConsultantUser::findOrFail($user);
        $user->update([
            'identity_verified' => 1,
            'identity_verified_at' => now(),
            'identity_rejection_reason' => null,
        ]);
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function rejectIdentity(Request $request, $user)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        $user = BiraConsultantUser::findOrFail($user);
        $user->update([
            'identity_verified' => 2,
            'identity_rejection_reason' => $request->rejection_reason,
            'identity_verified_at' => null,
        ]);
        return response()->json(['success' => true, 'data' => $user]);
    }
}

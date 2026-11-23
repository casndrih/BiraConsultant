<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCompanyApiController extends Controller
{
    public function index(Request $request)
    {
        $query = BiraConsultantCompany::withCount(['jobs']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $companies = $query->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $companies]);
    }

    public function show($slug)
    {
        $company = BiraConsultantCompany::with(['jobs', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $company]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
        ]);

        $company = BiraConsultantCompany::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'industry' => $request->industry,
            'description' => $request->description,
            'is_approved' => 1,
        ]);

        return response()->json(['success' => true, 'data' => $company], 201);
    }

    public function update(Request $request, $slug)
    {
        $company = BiraConsultantCompany::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'industry' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:5000',
        ]);

        $company->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'industry' => $request->industry,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'data' => $company]);
    }

    public function destroy($slug)
    {
        $company = BiraConsultantCompany::where('slug', $slug)->firstOrFail();
        $company->delete();
        return response()->json(['success' => true]);
    }

    public function approveVerification($slug)
    {
        $company = BiraConsultantCompany::where('slug', $slug)->firstOrFail();
        $company->update([
            'verification_status' => 1,
            'is_approved' => 1,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'verification_rejection_reason' => null,
        ]);
        return response()->json(['success' => true, 'data' => $company]);
    }

    public function rejectVerification(Request $request, $slug)
    {
        $company = BiraConsultantCompany::where('slug', $slug)->firstOrFail();
        $company->update([
            'verification_status' => 2,
            'is_approved' => 0,
            'verification_rejection_reason' => $request->input('rejection_reason'),
            'verified_at' => null,
            'verified_by' => null,
        ]);
        return response()->json(['success' => true, 'data' => $company]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\QJobsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminJobApiController extends Controller
{
    public function index(Request $request)
    {
        $query = QJobsJob::with(['company', 'category']);

        if ($request->filled('filter')) {
            match ($request->input('filter')) {
                'pending' => $query->where('approval_status', 'pending'),
                'approved' => $query->where('approval_status', 'approved'),
                'rejected' => $query->where('approval_status', 'rejected'),
                'expired' => $query->where('deadline', '<', now()),
                default => null,
            };
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('company', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $jobs = $query->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $jobs]);
    }

    public function show($slug)
    {
        $job = QJobsJob::with(['company', 'category', 'jobType', 'experienceLevel'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $job]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_id' => 'required|exists:qjobs_companies,id',
            'category_id' => 'nullable|exists:qjobs_job_categories,id',
            'job_type_id' => 'nullable|exists:qjobs_job_types,id',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:100',
        ]);

        $job = QJobsJob::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'company_id' => $request->company_id,
            'category_id' => $request->category_id,
            'job_type_id' => $request->job_type_id,
            'location' => $request->location,
            'salary' => $request->salary,
            'approval_status' => 'approved',
            'status' => 1,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'data' => $job], 201);
    }

    public function update(Request $request, $slug)
    {
        $job = QJobsJob::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:qjobs_job_categories,id',
            'job_type_id' => 'nullable|exists:qjobs_job_types,id',
            'location' => 'nullable|string|max:255',
            'salary' => 'nullable|string|max:100',
        ]);

        $job->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'job_type_id' => $request->job_type_id,
            'location' => $request->location,
            'salary' => $request->salary,
        ]);

        return response()->json(['success' => true, 'data' => $job]);
    }

    public function destroy($slug)
    {
        $job = QJobsJob::where('slug', $slug)->firstOrFail();
        $job->delete();
        return response()->json(['success' => true]);
    }

    public function approve($slug)
    {
        $job = QJobsJob::where('slug', $slug)->firstOrFail();
        $job->update(['approval_status' => 'approved']);
        return response()->json(['success' => true, 'data' => $job]);
    }

    public function reject(Request $request, $slug)
    {
        $job = QJobsJob::where('slug', $slug)->firstOrFail();
        $job->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);
        return response()->json(['success' => true, 'data' => $job]);
    }
}

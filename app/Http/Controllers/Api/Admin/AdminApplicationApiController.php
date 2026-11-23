<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantApplication;
use Illuminate\Http\Request;

class AdminApplicationApiController extends Controller
{
    public function index(Request $request)
    {
        $query = BiraConsultantApplication::with(['job.company', 'user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('job', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_id', $request->input('status'));
        }

        $applications = $query->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $applications]);
    }

    public function show($id)
    {
        $application = BiraConsultantApplication::with(['job.company', 'user'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $application]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|integer|between:1,6',
        ]);

        $application = BiraConsultantApplication::findOrFail($id);
        $application->update(['status_id' => $request->status_id]);

        return response()->json(['success' => true, 'data' => $application]);
    }

    public function destroy($id)
    {
        $application = BiraConsultantApplication::findOrFail($id);
        $application->delete();
        return response()->json(['success' => true]);
    }
}

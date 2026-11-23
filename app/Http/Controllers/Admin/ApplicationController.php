<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantApplication;
use App\Models\BiraConsultantApplicationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = BiraConsultantApplication::with(['user', 'job', 'status'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statuses = BiraConsultantApplicationStatus::all();

        return view('admin.applications.index', compact('applications', 'statuses'));
    }
    
    public function query(Request $request)
    {
        $query = trim($request->input('query-application', ''));

        $applications = BiraConsultantApplication::with(['user', 'job', 'status'])
            ->whereHas('job', function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statuses = BiraConsultantApplicationStatus::all();

        return view('admin.applications.index', compact('applications', 'statuses'));
    }

    public function edit($id)
    {
        $application = BiraConsultantApplication::with(['user', 'job.company', 'status'])->findOrFail($id);
        return response()->json($application);
    }

    public function update(Request $request)
    {
        $request->validate([
            'edit_application_id'      => 'required|exists:biraconsultant_applications,id',
            'edit_application_userId'  => 'required|exists:biraconsultant_users,id',
            'edit_application_jobId'   => 'required|exists:biraconsultant_jobs,id',
            'edit_application_status'  => 'required|integer|exists:biraconsultant_application_status,id',
        ]);

        $application = BiraConsultantApplication::findOrFail($request->edit_application_id);
        $application->update([
            'user_id'     => $request->edit_application_userId,
            'job_id'      => $request->edit_application_jobId,
            'status_id'   => $request->edit_application_status,
            'reviewed_at' => now(),
        ]);

        Session::flash('success', 'Application updated!');
        return redirect()->route('admin.applications.index');
    }

    public function delete(Request $request)
    {
    $application = BiraConsultantApplication::findOrFail($request->application_id);

    if ($application->cover_letter) {
        $relativePath = Str::after($application->cover_letter, 'storage/');
        if (Storage::exists($relativePath)) {
            Storage::delete($relativePath);
        }
    }

    $application->delete();

    Session::flash('success', 'Application deleted!');
    return redirect()->route('admin.applications.index');
    }
    
    public function downloadCoverLetter($fullPath)
    {
    $filename = basename($fullPath);
    $relativePath = 'secure/cover_letters/' . $filename;

    if (!Storage::exists($relativePath)) {
        abort(404, 'Cover letter not found.');
    }

    return Storage::response($relativePath, null, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $filename . '"'
    ]);
    }
    
}
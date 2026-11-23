<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QJobsApplication;
use App\Models\QJobsApplicationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = QJobsApplication::with(['user', 'job', 'status'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statuses = QJobsApplicationStatus::all();

        return view('admin.applications.index', compact('applications', 'statuses'));
    }
    
    public function query(Request $request)
    {
        $query = trim($request->input('query-application', ''));

        $applications = QJobsApplication::with(['user', 'job', 'status'])
            ->whereHas('job', function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statuses = QJobsApplicationStatus::all();

        return view('admin.applications.index', compact('applications', 'statuses'));
    }

    public function edit($id)
    {
        $application = QJobsApplication::with(['user', 'job.company', 'status'])->findOrFail($id);
        return response()->json($application);
    }

    public function update(Request $request)
    {
        $request->validate([
            'edit_application_id'      => 'required|exists:qjobs_applications,id',
            'edit_application_userId'  => 'required|exists:qjobs_users,id',
            'edit_application_jobId'   => 'required|exists:qjobs_jobs,id',
            'edit_application_status'  => 'required|integer|exists:qjobs_application_status,id',
        ]);

        $application = QJobsApplication::findOrFail($request->edit_application_id);
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
    $application = QJobsApplication::findOrFail($request->application_id);

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
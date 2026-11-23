<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QJobsCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    public function index()
    {
    $companies = QJobsCompany::orderBy('name', 'asc')->paginate(15);
    return view('admin.companies.index', compact('companies'));
    }

    public function query(Request $request)
    {
    $query = trim($request->input('query-company', ''));

    $companies = QJobsCompany::where('name', 'like', '%' . $query . '%')
        ->orWhere('industry', 'like', '%' . $query . '%')
        ->orWhere('location', 'like', '%' . $query . '%')
        ->orderBy('name', 'asc')
        ->paginate(15);

    return view('admin.companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'website' => 'nullable|url|max:255',
        'logo' => 'nullable|string|max:255',
        'industry' => 'nullable|string|max:255',
        'location' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'description' => 'nullable|string',
        'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
        'size' => 'nullable|string|max:100',
    ]);

    QJobsCompany::create($request->only([
        'name',
        'website',
        'logo',
        'industry',
        'location',
        'email',
        'phone',
        'description',
        'founded_year',
        'size',
    ]));

    Session::flash('success', 'Company created successfully.');
    return redirect()->route('admin.companies.index');
    }

    public function edit($slug)
    {
        $company = QJobsCompany::where('slug', $slug)->firstOrFail();
        return response()->json($company);
    }

    public function update(Request $request)
    {
        $request->validate([
            'edit_company_id' => 'required|exists:qjobs_companies,id',
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'size' => 'nullable|string|max:100',
        ]);

        $company = QJobsCompany::findOrFail($request->edit_company_id);
        $company->update($request->only([
            'name',
            'website',
            'logo',
            'industry',
            'location',
            'email',
            'phone',
            'description',
            'founded_year',
            'size',
        ]));

        Session::flash('success', 'Company updated successfully.');
        return redirect()->route('admin.companies.index');
    }

    public function delete($slug)
    {
        $company = QJobsCompany::where('slug', $slug)->firstOrFail();
        $company->delete();

        Session::flash('success', 'Company deleted successfully.');
        return redirect()->route('admin.companies.index');
    }

    // ===== Company Verification Review =====

    public function verification(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        $query = QJobsCompany::whereNotNull('registration_doc_path');

        $query->when($tab === 'pending', function ($q) {
            $q->where('verification_status', 0);
        })->when($tab === 'verified', function ($q) {
            $q->where('verification_status', 1);
        })->when($tab === 'rejected', function ($q) {
            $q->where('verification_status', 2);
        });

        $companies = $query->with('user')->orderBy('created_at', 'desc')->paginate(20);

        $counts = [
            'pending'  => QJobsCompany::whereNotNull('registration_doc_path')->where('verification_status', 0)->count(),
            'verified' => QJobsCompany::whereNotNull('registration_doc_path')->where('verification_status', 1)->count(),
            'rejected' => QJobsCompany::whereNotNull('registration_doc_path')->where('verification_status', 2)->count(),
        ];

        $activeTab = $tab;
        $pendingCount = $counts['pending'];

        return view('admin.companies.verification', compact('companies', 'tab', 'activeTab', 'counts', 'pendingCount'));
    }

    public function approveVerification(Request $request, QJobsCompany $company)
    {
        $company->update([
            'verification_status' => 1,
            'is_approved' => 1,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'verification_rejection_reason' => null,
        ]);

        return redirect()->route('admin.companies.verification')->with('success', "{$company->name} has been verified and approved.");
    }

    public function rejectVerification(Request $request, QJobsCompany $company)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $company->update([
            'verification_status' => 2,
            'is_approved' => 0,
            'verification_rejection_reason' => $request->rejection_reason,
            'verified_at' => null,
            'verified_by' => null,
        ]);

        return redirect()->route('admin.companies.verification')->with('success', "{$company->name}'s verification has been rejected.");
    }
}
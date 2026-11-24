<?php

namespace Tests\Feature;

use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantJobCategory;
use App\Models\BiraConsultantJobExperienceLevel;
use App\Models\BiraConsultantJobType;
use App\Models\BiraConsultantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CompanyPublicationRulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_company_directory_only_shows_approved_verified_companies_with_logos(): void
    {
        BiraConsultantCompany::create([
            'name' => 'Pending Smoke Test Employer',
            'slug' => 'pending-smoke-test-employer',
            'is_approved' => false,
            'verification_status' => 0,
            'logo' => 'images/companies/bsp-logo.svg',
        ]);

        BiraConsultantCompany::create([
            'name' => 'Logo Missing Smoke Test Employer',
            'slug' => 'logo-missing-smoke-test-employer',
            'is_approved' => true,
            'verification_status' => 1,
            'logo' => null,
        ]);

        $response = $this->getJson('/companies/list')->assertOk();

        $html = $response->json('html');

        $this->assertStringContainsString('Bank South Pacific', $html);
        $this->assertStringNotContainsString('Pending Smoke Test Employer', $html);
        $this->assertStringNotContainsString('Logo Missing Smoke Test Employer', $html);
    }

    public function test_active_job_scope_hides_jobs_from_unapproved_companies(): void
    {
        $company = BiraConsultantCompany::create([
            'name' => 'Unapproved Smoke Test Employer',
            'slug' => 'unapproved-smoke-test-employer',
            'is_approved' => false,
            'verification_status' => 0,
            'logo' => 'images/companies/bsp-logo.svg',
        ]);

        $job = BiraConsultantJob::create([
            'title' => 'Hidden Smoke Test Vacancy',
            'slug' => 'hidden-smoke-test-vacancy',
            'description' => 'Synthetic test vacancy.',
            'company_id' => $company->id,
            'category_id' => BiraConsultantJobCategory::query()->firstOrFail()->id,
            'job_type_id' => BiraConsultantJobType::query()->firstOrFail()->id,
            'experience_level_id' => BiraConsultantJobExperienceLevel::query()->firstOrFail()->id,
            'deadline' => now()->addDays(7)->toDateString(),
            'status' => 1,
            'approval_status' => 'approved',
        ]);

        $this->assertDatabaseHas('biraconsultant_jobs', ['id' => $job->id]);
        $this->assertFalse(BiraConsultantJob::active()->whereKey($job->id)->exists());
    }

    public function test_unverified_company_cannot_open_or_submit_job_posting(): void
    {
        $owner = BiraConsultantUser::create([
            'name' => 'Pending Company Owner',
            'slug' => 'pending-company-owner',
            'username' => 'pending-company-owner',
            'email' => 'pending.owner@biraconsultant.test',
            'password' => Hash::make('test-password'),
            'role' => 2,
            'gender' => '0',
        ]);

        BiraConsultantCompany::create([
            'name' => 'Pending Smoke Test Company',
            'slug' => 'pending-smoke-test-company',
            'user_id' => $owner->id,
            'is_approved' => false,
            'verification_status' => 0,
            'logo' => null,
        ]);

        $this->actingAs($owner)
            ->get('/company/jobs/create')
            ->assertRedirect('/company/verification');

        $this->actingAs($owner)
            ->post('/company/jobs', [])
            ->assertRedirect('/company/verification');
    }

    public function test_admin_requires_company_logo_and_complete_documents_before_approval(): void
    {
        $admin = BiraConsultantUser::where('email', 'admin@biraconsultant.test')->firstOrFail();

        $company = BiraConsultantCompany::create([
            'name' => 'Verification Gate Smoke Test',
            'slug' => 'verification-gate-smoke-test',
            'is_approved' => false,
            'verification_status' => 0,
            'registration_number' => 'REG-SMOKE-001',
            'registration_doc_path' => 'company_docs/test/registration.pdf',
            'tax_id_number' => 'TAX-SMOKE-001',
            'tax_doc_path' => 'company_docs/test/tax.pdf',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.companies.verification.approve', $company))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('biraconsultant_companies', [
            'id' => $company->id,
            'is_approved' => 0,
            'verification_status' => 0,
        ]);

        $company->update(['logo' => 'images/companies/bsp-logo.svg']);

        $this->actingAs($admin)
            ->patch(route('admin.companies.verification.approve', $company))
            ->assertRedirect(route('admin.companies.verification'));

        $this->assertDatabaseHas('biraconsultant_companies', [
            'id' => $company->id,
            'is_approved' => 1,
            'verification_status' => 1,
        ]);
    }

    public function test_verified_demo_company_with_logo_can_open_job_creation_form(): void
    {
        $owner = BiraConsultantUser::where('email', 'company1@biraconsultant.test')->firstOrFail();

        $this->actingAs($owner)
            ->get('/company/jobs/create')
            ->assertOk();
    }
}

<?php

namespace Tests\Feature;

use App\Models\BiraConsultantApplication;
use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantJobCategory;
use App\Models\BiraConsultantJobExperienceLevel;
use App\Models\BiraConsultantJobType;
use App\Models\BiraConsultantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExtendedMarketplaceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');
    }

    private function companyUser(string $email = 'company1@biraconsultant.test'): BiraConsultantUser
    {
        return BiraConsultantUser::where('email', $email)->firstOrFail();
    }

    private function createJob(BiraConsultantCompany $company, string $title): BiraConsultantJob
    {
        return BiraConsultantJob::create([
            'title' => $title,
            'slug' => str()->slug($title),
            'description' => 'Automated workflow test vacancy.',
            'company_id' => $company->id,
            'category_id' => BiraConsultantJobCategory::query()->firstOrFail()->id,
            'job_type_id' => BiraConsultantJobType::query()->firstOrFail()->id,
            'experience_level_id' => BiraConsultantJobExperienceLevel::query()->firstOrFail()->id,
            'deadline' => now()->addDays(20)->toDateString(),
            'status' => 1,
            'approval_status' => 'approved',
        ]);
    }

    public function test_company_can_edit_and_toggle_its_job_but_cannot_edit_another_company_job(): void
    {
        $owner = $this->companyUser();
        $company = BiraConsultantCompany::where('user_id', $owner->id)->firstOrFail();
        $job = $this->createJob($company, 'Extended company job edit test');

        $payload = [
            'title' => 'Extended company job edit test updated',
            'description' => 'Updated description.',
            'requirements' => 'Updated requirements.',
            'how_to_apply' => 'Apply online.',
            'category_id' => BiraConsultantJobCategory::query()->firstOrFail()->id,
            'job_type_id' => BiraConsultantJobType::query()->firstOrFail()->id,
            'experience_level_id' => BiraConsultantJobExperienceLevel::query()->firstOrFail()->id,
            'location' => 'Lae, Papua New Guinea',
            'deadline' => now()->addDays(20)->toDateString(),
            'duration' => 20,
        ];

        $this->actingAs($owner)->put('/company/jobs/' . $job->id, $payload)
            ->assertRedirect(route('company.dashboard'));
        $this->assertSame($payload['title'], $job->fresh()->title);

        $this->actingAs($owner)->patch('/company/jobs/' . $job->id . '/toggle')->assertRedirect();
        $this->assertSame(0, (int) $job->fresh()->status);

        $otherOwner = $this->companyUser('company2@biraconsultant.test');
        $this->actingAs($otherOwner)->put('/company/jobs/' . $job->id, $payload)->assertForbidden();
    }

    public function test_company_can_import_csv_jobs_and_invalid_rows_are_skipped(): void
    {
        $owner = $this->companyUser();

        $category = BiraConsultantJobCategory::query()->firstOrFail();
        $type = BiraConsultantJobType::query()->firstOrFail();
        $level = BiraConsultantJobExperienceLevel::query()->firstOrFail();

        $csv = implode("\n", [
            'title,description,category,job_type,experience_level,location,deadline,duration',
            '"CSV Workflow Vacancy","A valid imported job","' . $category->name . '","' . $type->name . '","' . $level->name . '","Lae",' . now()->addDays(30)->toDateString() . ',30',
            '"Invalid CSV Vacancy","Missing category","Unknown Category","' . $type->name . '","' . $level->name . '","Lae",' . now()->addDays(30)->toDateString() . ',30',
        ]);

        $this->actingAs($owner)->post('/company/jobs/import', [
            'jobs_file' => UploadedFile::fake()->createWithContent('workflow-jobs.csv', $csv),
        ])->assertRedirect(route('company.jobs.import'));

        $this->assertDatabaseHas('biraconsultant_jobs', [
            'title' => 'CSV Workflow Vacancy',
            'company_id' => BiraConsultantCompany::where('user_id', $owner->id)->value('id'),
            'approval_status' => 'pending',
        ]);
        $this->assertDatabaseMissing('biraconsultant_jobs', ['title' => 'Invalid CSV Vacancy']);
    }

    public function test_company_can_advance_application_stage_and_access_only_its_own_cv(): void
    {
        $owner = $this->companyUser();
        $company = BiraConsultantCompany::where('user_id', $owner->id)->firstOrFail();
        $job = $this->createJob($company, 'Extended applicant review test');
        $application = BiraConsultantApplication::create([
            'user_id' => BiraConsultantUser::where('email', 'martha.kila@biraconsultant.test')->value('id'),
            'job_id' => $job->id,
            'status_id' => 1,
            'cover_letter' => 'This is a synthetic cover letter for applicant workflow testing.',
            'cv_path' => 'workflow/cv.pdf',
        ]);
        Storage::disk('public')->put('workflow/cv.pdf', 'synthetic test PDF');

        $this->actingAs($owner)->patch('/company/application/' . $application->id . '/stage', [
            'status_id' => 3,
        ])->assertRedirect();
        $this->assertSame(3, (int) $application->fresh()->status_id);

        $this->actingAs($owner)->get('/company/application/' . $application->id . '/download/cv')->assertOk();

        $otherOwner = $this->companyUser('company2@biraconsultant.test');
        $this->actingAs($otherOwner)->get('/company/application/' . $application->id . '/download/cv')->assertForbidden();
    }

    public function test_admin_can_approve_company_only_after_required_documents_are_submitted(): void
    {
        $owner = $this->companyUser();
        $company = BiraConsultantCompany::where('user_id', $owner->id)->firstOrFail();
        $admin = BiraConsultantUser::where('email', 'admin@biraconsultant.test')->firstOrFail();
        $company->update([
            'verification_status' => 0,
            'is_approved' => 0,
            'registration_number' => null,
            'registration_doc_path' => null,
            'tax_id_number' => null,
            'tax_doc_path' => null,
        ]);

        $this->actingAs($admin)->from(route('admin.companies.verification'))
            ->patch('/admin/companies/' . $company->id . '/verification/approve')
            ->assertRedirect(route('admin.companies.verification'));
        $this->assertSame(0, (int) $company->fresh()->verification_status);

        $this->actingAs($owner)->post('/company/verification/upload', [
            'registration_number' => 'REG-EXT-TEST',
            'registration_doc' => UploadedFile::fake()->create('registration.pdf', 15, 'application/pdf'),
            'tax_id_number' => 'TIN-EXT-TEST',
            'tax_doc' => UploadedFile::fake()->create('tax.pdf', 15, 'application/pdf'),
        ])->assertRedirect(route('company.verification'));

        $this->actingAs($admin)->patch('/admin/companies/' . $company->id . '/verification/approve')
            ->assertRedirect(route('admin.companies.verification'));

        $this->assertSame(1, (int) $company->fresh()->verification_status);
        $this->assertSame(1, (int) $company->fresh()->is_approved);
    }
}

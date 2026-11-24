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
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MarketplaceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function createPublicTestCompany(): BiraConsultantCompany
    {
        $owner = BiraConsultantUser::create([
            'name' => 'Workflow Test Employer Owner',
            'slug' => 'workflow-test-employer-owner',
            'username' => 'workflow-test-employer-owner',
            'email' => 'workflow.owner@example.com',
            'password' => bcrypt('test-password'),
            'role' => 2,
            'gender' => '0',
        ]);

        return BiraConsultantCompany::create([
            'name' => 'Workflow Test Employer',
            'slug' => 'workflow-test-employer',
            'user_id' => $owner->id,
            'is_approved' => true,
            'verification_status' => 1,
            'logo' => 'images/companies/bsp-logo.svg',
        ]);
    }

    private function createPublicTestJob(string $title = 'Workflow Public Test Vacancy', string $approvalStatus = 'approved'): BiraConsultantJob
    {
        $company = $this->createPublicTestCompany();

        return BiraConsultantJob::create([
            'title' => $title,
            'slug' => str()->slug($title),
            'description' => 'Synthetic vacancy used to verify job seeker and moderation workflows.',
            'company_id' => $company->id,
            'category_id' => BiraConsultantJobCategory::query()->firstOrFail()->id,
            'job_type_id' => BiraConsultantJobType::query()->firstOrFail()->id,
            'experience_level_id' => BiraConsultantJobExperienceLevel::query()->firstOrFail()->id,
            'deadline' => now()->addDays(14)->toDateString(),
            'status' => 1,
            'approval_status' => $approvalStatus,
        ]);
    }

    public function test_job_seeker_can_save_and_unsave_a_job_without_changing_the_final_state(): void
    {
        $user = BiraConsultantUser::where('email', 'martha.kila@biraconsultant.test')->firstOrFail();
        $job = $this->createPublicTestJob();
        $wasSaved = $user->hasSavedJob($job->id);

        $first = $this->actingAs($user)->postJson("/user/job/{$job->id}/toggle-save")->assertOk();
        $this->assertSame(!$wasSaved, $first->json('saved'));

        $second = $this->actingAs($user)->postJson("/user/job/{$job->id}/toggle-save")->assertOk();
        $this->assertSame($wasSaved, $second->json('saved'));
        $this->assertSame($wasSaved, $user->fresh()->hasSavedJob($job->id));
    }

    public function test_job_seeker_can_apply_to_and_withdraw_from_an_active_job(): void
    {
        $user = BiraConsultantUser::where('email', 'martha.kila@biraconsultant.test')->firstOrFail();
        $job = $this->createPublicTestJob('Workflow Application Test Vacancy');

        $this->actingAs($user)
            ->post("/user/application/{$job->id}", [
                'cover_letter' => str_repeat('I am interested in this role and bring relevant experience. ', 2),
            ])
            ->assertRedirect(route('user.applications'));

        $application = BiraConsultantApplication::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->firstOrFail();

        $this->assertSame(1, (int) $application->status_id);

        $this->actingAs($user)
            ->delete("/user/application/{$application->id}")
            ->assertRedirect(route('user.applications'));

        $this->assertDatabaseMissing('biraconsultant_applications', ['id' => $application->id]);
    }

    public function test_verified_company_can_create_and_delete_a_pending_job(): void
    {
        $owner = BiraConsultantUser::where('email', 'company1@biraconsultant.test')->firstOrFail();

        $this->actingAs($owner)
            ->post('/company/jobs', [
                'title' => 'Workflow Smoke Test Vacancy',
                'description' => 'A synthetic vacancy used to verify company job posting and deletion.',
                'requirements' => 'Testing only.',
                'how_to_apply' => 'Apply through the platform.',
                'category_id' => BiraConsultantJobCategory::query()->firstOrFail()->id,
                'job_type_id' => BiraConsultantJobType::query()->firstOrFail()->id,
                'experience_level_id' => BiraConsultantJobExperienceLevel::query()->firstOrFail()->id,
                'location' => 'Lae, Papua New Guinea',
                'deadline' => now()->addDays(14)->toDateString(),
                'duration' => 14,
            ])
            ->assertRedirect(route('company.dashboard'));

        $job = BiraConsultantJob::where('title', 'Workflow Smoke Test Vacancy')->firstOrFail();
        $this->assertSame('pending', $job->approval_status);

        $this->actingAs($owner)
            ->delete("/company/jobs/{$job->id}")
            ->assertRedirect(route('company.dashboard'));

        $this->assertDatabaseMissing('biraconsultant_jobs', ['id' => $job->id]);
    }

    public function test_admin_can_approve_and_reject_a_company_job(): void
    {
        Notification::fake();

        $admin = BiraConsultantUser::where('email', 'admin@biraconsultant.test')->firstOrFail();
        $company = $this->createPublicTestCompany();

        $job = BiraConsultantJob::create([
            'title' => 'Admin Moderation Smoke Test',
            'slug' => 'admin-moderation-smoke-test',
            'description' => 'Synthetic job used to verify the moderation workflow.',
            'company_id' => $company->id,
            'category_id' => BiraConsultantJobCategory::query()->firstOrFail()->id,
            'job_type_id' => BiraConsultantJobType::query()->firstOrFail()->id,
            'experience_level_id' => BiraConsultantJobExperienceLevel::query()->firstOrFail()->id,
            'deadline' => now()->addDays(14)->toDateString(),
            'status' => 1,
            'approval_status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch("/admin/jobs/{$job->id}/approve")
            ->assertRedirect();

        $this->assertDatabaseHas('biraconsultant_jobs', [
            'id' => $job->id,
            'approval_status' => 'approved',
        ]);

        $this->actingAs($admin)
            ->patch("/admin/jobs/{$job->id}/reject", ['rejection_reason' => 'Synthetic moderation test completed.'])
            ->assertRedirect();

        $this->assertDatabaseHas('biraconsultant_jobs', [
            'id' => $job->id,
            'approval_status' => 'rejected',
        ]);
    }
}

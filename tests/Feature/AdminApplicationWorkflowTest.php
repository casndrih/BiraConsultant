<?php

namespace Tests\Feature;

use App\Models\BiraConsultantApplication;
use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApplicationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_delete_application_using_route_id(): void
    {
        $admin = BiraConsultantUser::where('email', 'admin@biraconsultant.test')->firstOrFail();
        $applicant = BiraConsultantUser::create([
            'name' => 'Admin Delete Regression Applicant',
            'slug' => 'admin-delete-regression-applicant',
            'username' => 'admin-delete-regression-applicant',
            'email' => 'admin-delete-regression@example.test',
            'password' => bcrypt('test-password'),
            'role' => 0,
            'gender' => '0',
        ]);
        $job = BiraConsultantJob::query()->firstOrFail();

        $application = BiraConsultantApplication::create([
            'user_id' => $applicant->id,
            'job_id' => $job->id,
            'status_id' => 1,
            'cover_letter' => null,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.application.delete', $application->id))
            ->assertRedirect(route('admin.applications.index'))
            ->assertSessionHas('success', 'Application deleted!');

        $this->assertDatabaseMissing('biraconsultant_applications', [
            'id' => $application->id,
        ]);
    }
}

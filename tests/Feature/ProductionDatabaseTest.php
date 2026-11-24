<?php

namespace Tests\Feature;

use App\Models\BiraConsultantApplication;
use App\Models\BiraConsultantJob;
use App\Models\BiraConsultantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductionDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_application_tables_are_created(): void
    {
        $tables = [
            'biraconsultant_users', 'biraconsultant_countries',
            'biraconsultant_companies', 'biraconsultant_jobs',
            'biraconsultant_job_categories', 'biraconsultant_job_types',
            'biraconsultant_job_experience_levels', 'biraconsultant_job_skills',
            'biraconsultant_applications', 'biraconsultant_application_status',
            'biraconsultant_user_work_experiences', 'biraconsultant_saved_jobs',
            'biraconsultant_company_followers', 'biraconsultant_user_skills',
            'biraconsultant_user_category_interests', 'biraconsultant_job_skill',
            'biraconsultant_blogs', 'biraconsultant_blog_categories',
            'biraconsultant_settings', 'notifications', 'password_reset_tokens',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing database table [{$table}].");
        }
    }

    public function test_seeder_builds_related_demo_records_and_can_run_twice(): void
    {
        $this->seed();
        $firstCounts = [
            'users' => BiraConsultantUser::count(),
            'companies' => \App\Models\BiraConsultantCompany::count(),
            'jobs' => BiraConsultantJob::count(),
            'applications' => BiraConsultantApplication::count(),
        ];

        $this->seed();

        $this->assertSame($firstCounts['users'], BiraConsultantUser::count());
        $this->assertSame($firstCounts['companies'], \App\Models\BiraConsultantCompany::count());
        $this->assertSame($firstCounts['jobs'], BiraConsultantJob::count());
        $this->assertSame($firstCounts['applications'], BiraConsultantApplication::count());

        $job = BiraConsultantJob::where('approval_status', 'approved')->firstOrFail();
        $this->assertNotNull($job->company);
        $this->assertNotNull($job->category);
        $this->assertNotNull($job->jobType);
        $this->assertNotNull($job->experienceLevel);
        $this->assertGreaterThan(0, $job->applications()->count());

        $applicant = BiraConsultantUser::where('role', 0)->firstOrFail();
        $this->assertGreaterThan(0, $applicant->skills()->count());
        $this->assertGreaterThan(0, $applicant->workExperiences()->count());
        $this->assertGreaterThan(0, $applicant->savedJobs()->count());
        $this->assertGreaterThan(0, $applicant->followingCompanies()->count());
    }

    public function test_saved_job_lookup_uses_the_pivot_user_id_column(): void
    {
        $this->seed();
        $user = BiraConsultantUser::where('role', 0)->firstOrFail();
        $job = $user->savedJobs()->firstOrFail();

        $this->assertTrue($job->isSavedBy($user->id));
        $this->assertFalse($job->isSavedBy(999999));
    }
}

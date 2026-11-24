<?php

namespace Tests\Feature;

use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountOperationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');
    }

    public function test_job_seeker_can_update_profile_and_upload_photo(): void
    {
        $user = BiraConsultantUser::where('email', 'martha.kila@biraconsultant.test')->firstOrFail();

        $this->actingAs($user)->post('/user/profile/update', [
            'name' => 'Martha Kila Updated',
            'email' => $user->email,
            'phone' => '+67570000001',
            'bio' => 'Profile workflow test.',
            'image' => UploadedFile::fake()->image('profile.png'),
        ])->assertRedirect(route('user.profile'));

        $user = $user->fresh();
        $this->assertSame('Martha Kila Updated', $user->name);
        Storage::disk('public')->assertExists($user->image);
    }

    public function test_company_can_update_profile_and_submit_verification_documents(): void
    {
        $owner = BiraConsultantUser::where('email', 'company1@biraconsultant.test')->firstOrFail();
        $company = BiraConsultantCompany::where('user_id', $owner->id)->firstOrFail();

        $this->actingAs($owner)->post('/company/settings', [
            'name' => 'Kumul Petroleum Workflow Test',
            'email' => 'workflow-company@example.com',
            'phone' => '+67570000002',
            'industry' => 'Energy',
            'description' => 'Company profile test.',
            'logo' => UploadedFile::fake()->image('company-logo.png'),
        ])->assertRedirect(route('company.settings'));

        $company = $company->fresh();
        $this->assertSame('Kumul Petroleum Workflow Test', $company->name);
        Storage::disk('public')->assertExists($company->logo);

        $this->actingAs($owner)->post('/company/verification/upload', [
            'registration_number' => 'REG-TEST-2026',
            'registration_doc' => UploadedFile::fake()->create('registration.pdf', 20, 'application/pdf'),
            'tax_id_number' => 'TIN-TEST-2026',
            'tax_doc' => UploadedFile::fake()->create('tax.pdf', 20, 'application/pdf'),
        ])->assertRedirect(route('company.verification'));

        $company = $company->fresh();
        $this->assertSame(0, (int) $company->verification_status);
        Storage::disk('public')->assertExists($company->registration_doc_path);
        Storage::disk('public')->assertExists($company->tax_doc_path);
    }
}

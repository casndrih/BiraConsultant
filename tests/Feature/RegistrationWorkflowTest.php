<?php

namespace Tests\Feature;

use App\Mail\VerifyEmail;
use App\Models\BiraConsultantCompany;
use App\Models\BiraConsultantCountry;
use App\Models\BiraConsultantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_applicant_can_register_and_receive_verification_email(): void
    {
        Mail::fake();
        $country = BiraConsultantCountry::where('iso', 'PG')->firstOrFail();

        $this->post('/signup', [
            'name' => 'Registration Workflow Applicant',
            'username' => 'workflow.applicant',
            'birthday' => '1998-05-12',
            'gender' => '0',
            'country_id' => $country->id,
            'email' => 'workflow.applicant@example.com',
            'password' => 'StrongPass525',
            'role' => '0',
        ])->assertRedirect('/');

        $user = BiraConsultantUser::where('email', 'workflow.applicant@example.com')->firstOrFail();
        $this->assertSame(0, (int) $user->role);
        $this->assertNotNull($user->email_verification_token);
        $this->assertNotNull($user->password);
        Mail::assertSent(VerifyEmail::class);
    }

    public function test_company_can_register_and_receives_a_pending_company_profile(): void
    {
        Mail::fake();

        $this->post('/signup', [
            'name' => 'Registration Workflow Company Owner',
            'username' => 'workflow.company',
            'company_name' => 'Workflow Registered Company',
            'email' => 'workflow.company@example.com',
            'password' => 'StrongPass525',
            'role' => '2',
        ])->assertRedirect(route('company.dashboard'));

        $user = BiraConsultantUser::where('email', 'workflow.company@example.com')->firstOrFail();
        $company = BiraConsultantCompany::where('user_id', $user->id)->firstOrFail();

        $this->assertSame(2, (int) $user->role);
        $this->assertSame('Workflow Registered Company', $company->name);
        $this->assertSame(0, (int) $company->is_approved);
        Mail::assertSent(VerifyEmail::class);
    }


    public function test_existing_user_password_reset_request_dispatches_reset_notification(): void
    {
        Notification::fake();

        $user = BiraConsultantUser::where('email', 'martha.kila@biraconsultant.test')->firstOrFail();

        $this->from('/')->post('/forgotpassword', [
            'email' => $user->email,
        ])->assertRedirect('/')->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_password_reset_request_uses_same_response_for_unknown_email(): void
    {
        Notification::fake();

        $this->from('/')->post('/forgotpassword', [
            'email' => 'not-registered@example.com',
        ])->assertRedirect('/')->assertSessionHas('status');

        Notification::assertNothingSent();
    }

    public function test_registration_cannot_self_assign_administrator_role(): void
    {
        Mail::fake();

        $this->from('/')->post('/signup', [
            'name' => 'Attempted Administrator',
            'username' => 'attempted.admin',
            'email' => 'attempted.admin@example.com',
            'password' => 'StrongPass525',
            'role' => '1',
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('biraconsultant_users', [
            'email' => 'attempted.admin@example.com',
        ]);
        Mail::assertNothingSent();
    }
}

<?php

namespace Tests\Feature;

use App\Mail\ContactFormMail;
use App\Providers\AppServiceProvider;
use App\Models\BiraConsultantContactMessage;
use App\Models\BiraConsultantSetting;
use App\Models\BiraConsultantUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactAndCaptchaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_confirmed_contact_recipient_and_public_contact_email_are_configured(): void
    {
        $this->assertSame('info@biraconsultant.com', config('mail.contact_to'));
        $this->assertDatabaseHas('biraconsultant_settings', [
            'key' => 'contact_email',
            'value' => 'info@biraconsultant.com',
        ]);
    }

    public function test_admin_can_update_contact_details_shown_on_about_and_contact_pages(): void
    {
        $admin = BiraConsultantUser::where('email', 'admin@biraconsultant.test')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.setting.update'), [
                'settings' => [
                    'contact_email' => 'hello@biraconsultant.com',
                    'contact_phone' => '+675 7000 1234',
                    'contact_address' => 'Lae, Morobe Province, Papua New Guinea',
                ],
            ])
            ->assertRedirect(route('admin.settings.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('biraconsultant_settings', [
            'key' => 'contact_email',
            'value' => 'hello@biraconsultant.com',
        ]);
        $this->assertDatabaseHas('biraconsultant_settings', [
            'key' => 'contact_phone',
            'value' => '+675 7000 1234',
        ]);
        $this->assertDatabaseHas('biraconsultant_settings', [
            'key' => 'contact_address',
            'value' => 'Lae, Morobe Province, Papua New Guinea',
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('hello@biraconsultant.com')
            ->assertSee('+675 7000 1234')
            ->assertSee('Lae, Morobe Province, Papua New Guinea');

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('hello@biraconsultant.com')
            ->assertSee('+675 7000 1234')
            ->assertSee('Lae, Morobe Province, Papua New Guinea');
    }

    public function test_admin_saved_smtp_settings_are_loaded_into_laravel_mail_configuration(): void
    {
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_driver'], ['value' => 'smtp']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_host'], ['value' => 'smtp.example.test']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_port'], ['value' => '465']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_username'], ['value' => 'mailer@example.test']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_password'], ['value' => 'test-secret']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_encryption'], ['value' => 'ssl']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_from_address'], ['value' => 'mailer@example.test']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_from_name'], ['value' => 'BiraConsultant Test']);

        app()->getProvider(AppServiceProvider::class)->boot();

        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.example.test', config('mail.mailers.smtp.host'));
        $this->assertSame(465, config('mail.mailers.smtp.port'));
        $this->assertSame('mailer@example.test', config('mail.mailers.smtp.username'));
        $this->assertSame('test-secret', config('mail.mailers.smtp.password'));
        $this->assertSame('ssl', config('mail.mailers.smtp.encryption'));
        $this->assertSame('mailer@example.test', config('mail.from.address'));
        $this->assertSame('BiraConsultant Test', config('mail.from.name'));
    }

    public function test_admin_smtp_test_handles_transport_exceptions_without_returning_http_500(): void
    {
        $admin = BiraConsultantUser::where('email', 'admin@biraconsultant.test')->firstOrFail();
        BiraConsultantSetting::updateOrCreate(['key' => 'contact_email'], ['value' => 'info@biraconsultant.com']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_driver'], ['value' => 'smtp']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_host'], ['value' => 'smtp.example.test']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_port'], ['value' => '587']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_username'], ['value' => 'mailer@example.test']);
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_password'], ['value' => 'test-secret']);
        app()->getProvider(AppServiceProvider::class)->boot();

        Mail::shouldReceive('raw')
            ->once()
            ->andThrow(new \RuntimeException('SMTP connection refused'));

        $this->actingAs($admin)
            ->post(route('admin.settings.email.test'))
            ->assertRedirect(route('admin.settings.index'))
            ->assertSessionHas('error', 'SMTP test failed: the mail transport could not send the message. Check the saved SMTP host, port, encryption and mailbox credentials.');
    }

    public function test_blank_mail_password_submission_keeps_existing_saved_password(): void
    {
        $admin = BiraConsultantUser::where('email', 'admin@biraconsultant.test')->firstOrFail();
        BiraConsultantSetting::updateOrCreate(['key' => 'mail_password'], ['value' => 'existing-secret']);

        $this->actingAs($admin)
            ->post(route('admin.setting.update'), [
                'settings' => [
                    'mail_driver' => 'smtp',
                    'mail_password' => '',
                ],
            ])
            ->assertRedirect(route('admin.settings.index'));

        $this->assertDatabaseHas('biraconsultant_settings', [
            'key' => 'mail_password',
            'value' => 'existing-secret',
        ]);
    }

    public function test_admin_contact_email_setting_rejects_invalid_email(): void
    {
        $admin = BiraConsultantUser::where('email', 'admin@biraconsultant.test')->firstOrFail();

        $this->actingAs($admin)
            ->from(route('admin.settings.index'))
            ->post(route('admin.setting.update'), [
                'settings' => ['contact_email' => 'not-an-email'],
            ])
            ->assertSessionHasErrors('settings.contact_email');
    }

    public function test_contact_form_persists_message_when_no_notification_recipient_is_configured(): void
    {
        config(['mail.contact_to' => null]);
        Mail::fake();

        $this->from(route('contact'))->post(route('contact.submit'), [
            'name' => 'Test Sender',
            'email' => 'sender@example.com',
            'subject' => 'Website enquiry',
            'message' => str_repeat('Please contact me about the service. ', 2),
            'website' => '',
        ])->assertRedirect(route('contact'))
          ->assertSessionHas('success');

        $this->assertDatabaseHas('biraconsultant_contact_messages', [
            'email' => 'sender@example.com',
            'subject' => 'Website enquiry',
        ]);
        Mail::assertNothingSent();
    }

    public function test_contact_form_sends_notification_when_recipient_is_configured(): void
    {
        config(['mail.contact_to' => 'inbox@example.com']);
        Mail::fake();

        $this->post(route('contact.submit'), [
            'name' => 'Test Sender',
            'email' => 'sender@example.com',
            'subject' => 'Website enquiry',
            'message' => str_repeat('Please contact me about the service. ', 2),
            'website' => '',
        ])->assertRedirect(route('contact'));

        $this->assertDatabaseCount('biraconsultant_contact_messages', 1);
        Mail::assertSent(ContactFormMail::class);
    }

    public function test_contact_form_honeypot_rejects_automated_submission(): void
    {
        $this->post(route('contact.submit'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'subject' => 'Spam',
            'message' => str_repeat('Automated message. ', 3),
            'website' => 'https://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseCount('biraconsultant_contact_messages', 0);
    }

    public function test_contact_inbox_is_admin_only_and_marks_opened_messages_as_read(): void
    {
        $message = BiraConsultantContactMessage::create([
            'name' => 'Inbox Test Sender',
            'email' => 'inbox.sender@example.com',
            'subject' => 'Admin inbox visibility test',
            'message' => 'This message verifies the protected administrator inbox.',
        ]);

        $jobSeeker = BiraConsultantUser::where('email', 'martha.kila@biraconsultant.test')->firstOrFail();
        $admin = BiraConsultantUser::where('email', 'admin@biraconsultant.test')->firstOrFail();

        $this->actingAs($jobSeeker)
            ->get(route('admin.contact-messages.index'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.contact-messages.index'))
            ->assertOk()
            ->assertSee('Admin inbox visibility test');

        $this->actingAs($admin)
            ->get(route('admin.contact-messages.show', $message))
            ->assertOk()
            ->assertSee('Inbox Test Sender');

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_enabled_recaptcha_fails_closed_when_secret_key_is_missing(): void
    {
        BiraConsultantSetting::updateOrCreate(['key' => 'recaptcha_enabled'], ['value' => '1']);
        BiraConsultantSetting::updateOrCreate(['key' => 'recaptcha_secret_key'], ['value' => '']);

        $this->from(route('contact'))->post(route('contact.submit'), [
            'name' => 'Test Sender',
            'email' => 'sender@example.com',
            'subject' => 'Website enquiry',
            'message' => str_repeat('Please contact me about the service. ', 2),
            'g-recaptcha-response' => 'non-empty-but-unverifiable-token',
            'website' => '',
        ])->assertRedirect(route('contact'))
          ->assertSessionHas('error');

        $this->assertDatabaseCount('biraconsultant_contact_messages', 0);
    }
}

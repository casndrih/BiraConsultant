<?php

namespace Tests\Feature;

use App\Mail\ContactFormMail;
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

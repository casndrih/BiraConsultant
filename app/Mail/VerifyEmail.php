<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationLink;

    public function __construct($user, $token)
    {
        $this->user = $user;
        // Route is registered as email.verify (GET /verify/{token}) in routes/web.php
        $this->verificationLink = route('email.verify', ['token' => $token]);
    }

    public function build()
    {
        return $this->subject('Verify Your Email Address')
                    ->view('mail.verify-email', [
                        'user' => $this->user,
                        'verificationLink' => $this->verificationLink
                    ]);
    }
}

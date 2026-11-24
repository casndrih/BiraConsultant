<?php

namespace App\\Mail;

use App\\Models\\BiraConsultantContactMessage;
use Illuminate\\Bus\\Queueable;
use Illuminate\\Mail\\Mailable;
use Illuminate\\Queue\\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public BiraConsultantContactMessage $contactMessage)
    {
    }

    public function build()
    {
        return $this->subject('BiraConsultant contact: ' . $this->contactMessage->subject)
            ->replyTo($this->contactMessage->email, $this->contactMessage->name)
            ->view('mail.contact-form');
    }
}

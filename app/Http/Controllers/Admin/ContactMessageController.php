<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiraConsultantContactMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = BiraConsultantContactMessage::latest()->paginate(20);

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function show(BiraConsultantContactMessage $contactMessage)
    {
        if (!$contactMessage->read_at) {
            $contactMessage->update(['read_at' => now()]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }
}

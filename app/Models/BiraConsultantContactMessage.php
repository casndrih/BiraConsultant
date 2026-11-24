<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;

class BiraConsultantContactMessage extends Model
{
    protected $table = 'biraconsultant_contact_messages';

    protected $fillable = ['name', 'email', 'subject', 'message', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];
}

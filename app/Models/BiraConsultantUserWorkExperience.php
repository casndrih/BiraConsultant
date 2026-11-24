<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantUserWorkExperience extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_user_work_experiences';

    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'description',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(BiraConsultantUser::class);
    }
}
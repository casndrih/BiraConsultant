<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantJobSkill extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_job_skills';

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(BiraConsultantUser::class, 'biraconsultant_user_skills', 'skill_id', 'user_id');
    }
}
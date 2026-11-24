<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantJobExperienceLevel extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_job_experience_levels';

    protected $fillable = ['name'];

    public function jobs()
    {
        return $this->hasMany(BiraConsultantJob::class, 'experience_level_id');
    }
}
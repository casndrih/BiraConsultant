<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QJobsJobSkill extends Model
{
    use HasFactory;

    protected $table = 'qjobs_job_skills';

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(QJobsUser::class, 'qjobs_user_skills', 'skill_id', 'user_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QJobsJobExperienceLevel extends Model
{
    use HasFactory;

    protected $table = 'qjobs_job_experience_levels';

    protected $fillable = ['name'];

    public function jobs()
    {
        return $this->hasMany(QJobsJob::class, 'experience_level_id');
    }
}
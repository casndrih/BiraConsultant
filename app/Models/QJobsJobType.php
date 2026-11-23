<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QJobsJobType extends Model
{
    use HasFactory;

    protected $table = 'qjobs_job_types';

    protected $fillable = ['name'];

    public function jobs()
    {
        return $this->hasMany(QJobsJob::class, 'job_type_id');
    }
}
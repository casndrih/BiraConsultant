<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QJobsJobCategory extends Model
{
    use HasFactory;

    protected $table = 'qjobs_job_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function jobs()
    {
        return $this->hasMany(QJobsJob::class, 'category_id');
    }
}
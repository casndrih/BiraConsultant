<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantJobType extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_job_types';

    protected $fillable = ['name'];

    public function jobs()
    {
        return $this->hasMany(BiraConsultantJob::class, 'job_type_id');
    }
}
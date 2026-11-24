<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantJobCategory extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_job_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function jobs()
    {
        return $this->hasMany(BiraConsultantJob::class, 'category_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantApplication extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_applications';

    protected $fillable = [
        'user_id',
        'job_id',
        'status_id',
        'reviewed_at',
        'cover_letter',
        'cv_path',
        'resume_path',
        'cover_letter_path',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public $timestamps = true;

    // Accessor for formatted date
    public function getDateAttribute()
    {
        return $this->created_at->format('Y M d');
    }

    // Accessor for formatted time
    public function getTimeAttribute()
    {
        return $this->created_at->format('h:i A');
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(BiraConsultantUser::class, 'user_id');
    }

    public function job()
    {
        return $this->belongsTo(BiraConsultantJob::class, 'job_id');
    }

    public function status()
    {
        return $this->belongsTo(BiraConsultantApplicationStatus::class, 'status_id');
    }

    // Human-readable status label
    public function getStatusLabelAttribute()
    {
        return $this->status ? $this->status->label : 'Unknown';
    }

    public function getStatusColorClassAttribute()
    {
        return $this->status ? $this->status->color_class : 'bg-gray-100 text-gray-700';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantJob extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_jobs';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'requirements',
        'how_to_apply',
        'company_id',
        'category_id',
        'location',
        'job_type_id',
        'salary',
        'experience_level_id',
        'deadline',
        'status',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'duration',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = self::generateUniqueSlug($job->title);
            }
        });
        static::updating(function ($job) {
            if ($job->isDirty('title') && empty($job->slug)) {
                $job->slug = self::generateUniqueSlug($job->title);
            }
        });
    }

    public static function generateUniqueSlug($title)
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();
        return $count > 0 ? $slug . '-' . ($count + 1) : $slug;
    }

    protected $casts = [
        'deadline' => 'date',
        'approved_at' => 'datetime',
        'status' => 'boolean',
    ];

    public $timestamps = true;

    public function getDateAttribute()
    {
        return $this->created_at->format('Y M d');
    }

    public function getTimeAttribute()
    {
        return $this->created_at->format('h:i A');
    }

    public function company()
    {
        return $this->belongsTo(BiraConsultantCompany::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasMany(BiraConsultantApplication::class, 'job_id');
    }

    public function category()
    {
        return $this->belongsTo(BiraConsultantJobCategory::class, 'category_id');
    }

    public function jobType()
    {
        return $this->belongsTo(BiraConsultantJobType::class, 'job_type_id');
    }

    public function experienceLevel()
    {
        return $this->belongsTo(BiraConsultantJobExperienceLevel::class, 'experience_level_id');
    }

    public function skills()
    {
        return $this->belongsToMany(BiraConsultantJobSkill::class, 'biraconsultant_job_skill', 'job_id', 'skill_id');
    }

    public function savedBy()
    {
        return $this->belongsToMany(BiraConsultantUser::class, 'biraconsultant_saved_jobs', 'job_id', 'user_id');
    }

    public function isSavedBy($userId)
    {
        return $this->savedBy()->where('biraconsultant_user_id', $userId)->exists();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1)
                     ->where('approval_status', 'approved')
                     ->where(function ($q) {
                         $q->whereNull('deadline')
                           ->orWhere('deadline', '>=', now());
                     });
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeExpired($query)
    {
        return $query->where('deadline', '<', now());
    }

    public function isExpired()
    {
        return $this->deadline && $this->deadline->isPast();
    }

    public function isVisible()
    {
        return $this->status == 1
            && $this->approval_status === 'approved'
            && !$this->isExpired();
    }

    public function approvalStatusLabel()
    {
        return match ($this->approval_status) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Pending',
        };
    }

    public function approvalStatusColor()
    {
        return match ($this->approval_status) {
            'approved' => 'text-green-600 bg-green-100',
            'rejected' => 'text-red-600 bg-red-100',
            default => 'text-yellow-600 bg-yellow-100',
        };
    }

    public function expiresAt()
    {
        if ($this->deadline) {
            return $this->deadline;
        }
        return $this->approved_at
            ? $this->approved_at->addDays((int) $this->duration)
            : $this->created_at->addDays((int) $this->duration);
    }
}
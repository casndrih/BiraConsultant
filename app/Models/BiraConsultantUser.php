<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'biraconsultant_users';

    protected $fillable = [
        'name',
        'slug',
        'username',
        'gender',
        'email',
        'google_id',
        'phone',
        'password',
        'role',
        'image',
        'cover',
        'bio',
        'website',
        'high_school',
        'college_university',
        'doc',
        'birthday',
        'country_id',
        'state',
        'referral_code',
        'email_verification_token',
        // Identity/NID verification
        'nid_number',
        'identity_document_type',
        'nid_front_path',
        'nid_back_path',
        'identity_verified',
        'identity_verified_at',
        'identity_rejection_reason',
        // Phone verification
        'phone_verification_code',
        'phone_verified',
        'phone_verified_at',
        // Notification preferences
        'notify_email',
        'notify_followed_company_jobs',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = self::generateUniqueSlug($user->name);
            }
        });
        static::updating(function ($user) {
            if ($user->isDirty('name') && empty($user->slug)) {
                $user->slug = self::generateUniqueSlug($user->name);
            }
        });
    }

    public static function generateUniqueSlug($name)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $count = static::where('slug', 'like', $slug . '%')->count();
        return $count > 0 ? $slug . '-' . ($count + 1) : $slug;
    }

    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'identity_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'role' => 'integer',
        'birthday' => 'date',
        'identity_verified' => 'integer',
        'phone_verified' => 'boolean',
    ];

    public $timestamps = true;

    // Accessor for date
    public function getDateAttribute()
    {
        return $this->created_at->format('Y M d');
    }

    // Accessor for time
    public function getTimeAttribute()
    {
        return $this->created_at->format('h:i A');
    }

    public function skills()
    {
        return $this->belongsToMany(BiraConsultantJobSkill::class, 'biraconsultant_user_skills', 'user_id', 'skill_id');
    }

    public function country()
    {
        return $this->belongsTo(BiraConsultantCountry::class, 'country_id');
    }

    public function applications()
    {
        return $this->hasMany(BiraConsultantApplication::class, 'user_id');
    }

    public function workExperiences()
    {
        return $this->hasMany(BiraConsultantUserWorkExperience::class, 'user_id');
    }

    public function interests()
    {
        return $this->belongsToMany(BiraConsultantJobCategory::class, 'biraconsultant_user_category_interests', 'user_id', 'category_id');
    }

    public function savedJobs()
    {
        return $this->belongsToMany(BiraConsultantJob::class, 'biraconsultant_saved_jobs', 'user_id', 'job_id')
            ->withTimestamps();
    }

    public function companies()
    {
        return $this->hasMany(BiraConsultantCompany::class, 'user_id');
    }

    public function company()
    {
        return $this->hasOne(BiraConsultantCompany::class, 'user_id');
    }

    public function followingCompanies()
    {
        return $this->belongsToMany(BiraConsultantCompany::class, 'biraconsultant_company_followers', 'user_id', 'company_id')
            ->withTimestamps();
    }

    public function isFollowingCompany($companyId)
    {
        return $this->followingCompanies()->where('company_id', $companyId)->exists();
    }

    public function followCompany(BiraConsultantCompany $company)
    {
        return $this->followingCompanies()->syncWithoutDetaching([$company->id]);
    }

    public function unfollowCompany($companyId)
    {
        return $this->followingCompanies()->detach($companyId);
    }

    public function notificationPreferences()
    {
        return [
            'email' => (bool) $this->notify_email,
            'followed_company_jobs' => (bool) $this->notify_followed_company_jobs,
        ];
    }

    public function isAdmin()
    {
        return $this->role === 1;
    }

    public function isJobSeeker()
    {
        return $this->role === 0;
    }

    public function hasSavedJob($jobId)
    {
        return $this->savedJobs()->where('job_id', $jobId)->exists();
    }

    public function hasAppliedTo($jobId)
    {
        return $this->applications()->where('job_id', $jobId)->exists();
    }

    // ===== Verification Helpers =====

    public function getIdentityVerificationStatusLabelAttribute()
    {
        return match ((int) $this->identity_verified) {
            1 => 'Verified',
            2 => 'Rejected',
            default => 'Pending',
        };
    }

    public function getIdentityVerificationStatusColorAttribute()
    {
        return match ((int) $this->identity_verified) {
            1 => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            2 => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        };
    }

    public function isIdentityVerified()
    {
        return (int) $this->identity_verified === 1;
    }

    public function isPhoneVerified()
    {
        return (bool) $this->phone_verified;
    }

    public function getVerificationProgressAttribute()
    {
        $steps = [
            !empty($this->email_verified_at),
            !empty($this->phone) && (bool) $this->phone_verified,
            !empty($this->nid_number) && (int) $this->identity_verified === 1,
        ];
        $completed = count(array_filter($steps));
        return round(($completed / count($steps)) * 100);
    }

    public function getProfileCompletionAttribute()
    {
        $fields = ['name', 'email', 'phone', 'bio', 'image', 'country_id', 'birthday'];
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filled++;
            }
        }
        return round(($filled / count($fields)) * 100);
    }
}

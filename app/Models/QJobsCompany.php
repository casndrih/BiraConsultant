<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QJobsCompany extends Model
{
    use HasFactory;

    protected $table = 'qjobs_companies';

    protected $fillable = [
        'name',
        'slug',
        'website',
        'logo',
        'industry',
        'location',
        'city',
        'country_id',
        'user_id',
        'address',
        'email',
        'phone',
        'description',
        'founded_year',
        'size',
        'is_approved',
        'latitude',
        'longitude',
        // Verification
        'registration_number',
        'registration_doc_path',
        'tax_id_number',
        'tax_doc_path',
        'verification_status',
        'verification_rejection_reason',
        'verified_at',
        'verified_by',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = self::generateUniqueSlug($company->name);
            }
        });
        static::updating(function ($company) {
            if ($company->isDirty('name') && empty($company->slug)) {
                $company->slug = self::generateUniqueSlug($company->name);
            }
        });
    }

    public static function generateUniqueSlug($name)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $count = static::where('slug', 'like', $slug . '%')->count();
        return $count > 0 ? $slug . '-' . ($count + 1) : $slug;
    }

    protected $casts = [
        'is_approved' => 'boolean',
        'verification_status' => 'integer',
        'verified_at' => 'datetime',
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

    public function jobs()
    {
        return $this->hasMany(QJobsJob::class, 'company_id');
    }

    public function country()
    {
        return $this->belongsTo(QJobsCountry::class, 'country_id');
    }

    public function user()
    {
        return $this->belongsTo(QJobsUser::class, 'user_id');
    }

    public function activeJobs()
    {
        return $this->hasMany(QJobsJob::class)->where('status', 1);
    }

    public function followers()
    {
        return $this->belongsToMany(QJobsUser::class, 'qjobs_company_followers', 'company_id', 'user_id')
            ->withTimestamps();
    }

    public function followerCount()
    {
        return $this->followers()->count();
    }

    // ===== Verification Helpers =====

    public function getVerificationStatusLabelAttribute()
    {
        return match ((int) $this->verification_status) {
            1 => 'Verified',
            2 => 'Rejected',
            default => 'Pending',
        };
    }

    public function getVerificationStatusColorAttribute()
    {
        return match ((int) $this->verification_status) {
            1 => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            2 => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        };
    }

    public function isVerified()
    {
        return (int) $this->verification_status === 1;
    }
}
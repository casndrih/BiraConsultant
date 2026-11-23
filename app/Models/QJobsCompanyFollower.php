<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BiraConsultantCompanyFollower extends Pivot
{
    protected $table = 'biraconsultant_company_followers';

    protected $fillable = [
        'user_id',
        'company_id',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(BiraConsultantUser::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(BiraConsultantCompany::class, 'company_id');
    }
}
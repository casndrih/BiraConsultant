<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class QJobsCompanyFollower extends Pivot
{
    protected $table = 'qjobs_company_followers';

    protected $fillable = [
        'user_id',
        'company_id',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(QJobsUser::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(QJobsCompany::class, 'company_id');
    }
}
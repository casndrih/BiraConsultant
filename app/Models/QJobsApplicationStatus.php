<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QJobsApplicationStatus extends Model
{
    use HasFactory;

    protected $table = 'qjobs_application_status';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id', 'label', 'color_class'];

    public $timestamps = true;

    public function applications()
    {
        return $this->hasMany(QJobsApplication::class, 'status_id');
    }
}
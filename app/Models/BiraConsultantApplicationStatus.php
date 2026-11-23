<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantApplicationStatus extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_application_status';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id', 'label', 'color_class'];

    public $timestamps = true;

    public function applications()
    {
        return $this->hasMany(BiraConsultantApplication::class, 'status_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiraConsultantCountry extends Model
{
    protected $table = 'biraconsultant_countries';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'iso', 'iso3', 'dial', 'currency', 'currency_name'];
    
    public $timestamps = true;
    // Accessor for date
    public function getDateAttribute()
    {
        return $this->created_at->format('Y M d'); // 'M' for short month name
    }

    // Accessor for time
    public function getTimeAttribute()
    {
        return $this->created_at->format('h:i A'); // Format with AM/PM
    }
    
}
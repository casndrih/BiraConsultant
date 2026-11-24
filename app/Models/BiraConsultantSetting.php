<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantSetting extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = true;

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, string $default = ''): string
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
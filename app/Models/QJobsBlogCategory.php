<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QJobsBlogCategory extends Model
{
    use HasFactory;

    protected $table = 'qjobs_blog_categories';

    protected $fillable = ['name', 'slug', 'description'];

    public function blogs()
    {
        return $this->hasMany(QJobsBlog::class, 'category_id');
    }
}
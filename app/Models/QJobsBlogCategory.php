<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiraConsultantBlogCategory extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_blog_categories';

    protected $fillable = ['name', 'slug', 'description'];

    public function blogs()
    {
        return $this->hasMany(BiraConsultantBlog::class, 'category_id');
    }
}
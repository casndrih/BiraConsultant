<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class BiraConsultantBlog extends Model
{
    use HasFactory;

    protected $table = 'biraconsultant_blogs';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'author_id',
        'category_id',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    // Relationships

    public function author()
    {
        return $this->belongsTo(BiraConsultantUser::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(BiraConsultantBlogCategory::class, 'category_id');
    }

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

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
            default => 'Unknown',
        };
    }
}
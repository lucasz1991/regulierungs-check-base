<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Like;

class Post extends Model
{
    protected $fillable = [
        'type',
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_image',
        'user_id',
        'category_id',
        'published',
        'published_at',
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    // 📌 Autor (z. B. Admin)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }


    // 💬 Kommentare
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function comments_count()
    {
        return $this->morphMany(Comment::class, 'commentable')->count();
    }

    public function likes_count()
    {
        return $this->morphMany(Like::class, 'likeable')->count();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedBy($user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }


    // 🔍 Nur veröffentlichte Beiträge
    public function scopePublished($query)
    {
        return $query->where('published', true)->whereNotNull('published_at');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // 📸 URL zum Bild (oder Fallback)
    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-post.jpg');
    }

    // ✂️ Vorschau aus dem Body generieren
    public function getExcerptPreviewAttribute()
    {
        return $this->excerpt ?? Str::limit(strip_tags($this->body), 150);
    }
}

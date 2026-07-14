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
        'news_category_id',
        'pagebuilder_project_id',
        'published',
        'published_at',
        'layout',
        'images',
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
        'images' => 'array',
    ];

    public const NEWS_LAYOUTS = [
        'image_top',
        'image_bottom',
        'image_left',
        'image_right',
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

    public function newsCategory()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function pagebuilderProject()
    {
        return $this->belongsTo(PagebuilderProject::class, 'pagebuilder_project_id');
    }

    // ⏱️ Lesezeit in Minuten (aus Pagebuilder-HTML oder Body, ~200 Wörter/Minute)
    public function getReadingTimeMinutesAttribute(): int
    {
        $html = $this->pagebuilder_project_id ? optional($this->pagebuilderProject)->cleaned_html : null;
        $text = strip_tags(($html ?: '') . ' ' . ($this->body ?? ''));
        $words = str_word_count($text);

        return max(1, (int) ceil($words / 200));
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
        return $query
            ->where('published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
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

    public function newsImages(): array
    {
        $images = collect($this->images ?? [])
            ->filter(fn ($image) => is_array($image) && !empty($image['path']))
            ->sortBy(fn ($image) => (int) ($image['sort'] ?? 0))
            ->values()
            ->map(function ($image) {
                $path = ltrim((string) $image['path'], '/');

                return [
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                    'alt' => $image['alt'] ?? $this->title,
                    'caption' => $image['caption'] ?? null,
                    'sort' => (int) ($image['sort'] ?? 0),
                ];
            })
            ->all();

        if ($images === [] && $this->cover_image) {
            $path = ltrim($this->cover_image, '/');

            return [[
                'path' => $path,
                'url' => asset('storage/' . $path),
                'alt' => $this->title,
                'caption' => null,
                'sort' => 0,
            ]];
        }

        return $images;
    }

    public function firstNewsImage(): ?array
    {
        return $this->newsImages()[0] ?? null;
    }

    // ✂️ Vorschau aus dem Body generieren
    public function getExcerptPreviewAttribute()
    {
        return $this->excerpt ?? Str::limit(strip_tags($this->body), 150);
    }
}

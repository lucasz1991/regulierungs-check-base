<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'body',
        'commentable_id',
        'commentable_type',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
}

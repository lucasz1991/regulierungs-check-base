<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimRatingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_rating_id',
        'user_id',
        'reason',
        'comment',
        'status',
    ];

    public function claimRating()
    {
        return $this->belongsTo(ClaimRating::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

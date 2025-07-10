<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tag',
        'description',
        'positivity',
    ];
}

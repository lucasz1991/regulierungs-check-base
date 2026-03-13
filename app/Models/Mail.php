<?php

namespace App\Models;

use App\Jobs\ProcessMailJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Mail extends Model
{
    protected $fillable = [
        'type',
        'status',
        'content',
        'recipients',
    ];

    protected $casts = [
        'content' => 'json',
        'recipients' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($mail) {
            ProcessMailJob::dispatch($mail);
        });
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}

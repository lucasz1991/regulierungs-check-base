<?php

namespace App\Models;

use App\Enums\PromotionWinStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class PromotionParticipation extends Model
{
    use HasFactory;

    protected $table = 'participations';

    protected $fillable = ['campaign_id', 'user_id', 'public_id'];

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(PromotionCampaign::class, 'campaign_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wins(): HasMany
    {
        return $this->hasMany(PromotionWin::class, 'participation_id');
    }

    public function currentWin(): HasOne
    {
        return $this->hasOne(PromotionWin::class, 'participation_id')->latestOfMany();
    }

    public function win(): HasOne
    {
        return $this->currentWin();
    }

    public function prize(): HasOneThrough
    {
        return $this->hasOneThrough(
            PromotionPrize::class,
            PromotionWin::class,
            'participation_id',
            'id',
            'id',
            'prize_id',
        )->orderByDesc('wins.id');
    }

    public function getStatusAttribute(): ?PromotionWinStatus
    {
        return $this->currentWin?->status;
    }

    public function getConfirmedAtAttribute(): mixed
    {
        return $this->currentWin?->confirmed_at;
    }

    public function getFulfilledAtAttribute(): mixed
    {
        return $this->currentWin?->fulfilled_at;
    }
}

<?php

namespace App\Models;

use App\Enums\PromotionFulfillmentMode;
use App\Enums\PromotionWinStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromotionWin extends Model
{
    use HasFactory;

    protected $table = 'wins';

    protected $guarded = ['id'];

    protected $hidden = ['token_hash', 'claim_key'];

    protected $casts = [
        'status' => PromotionWinStatus::class,
        'fulfillment_mode_snapshot' => PromotionFulfillmentMode::class,
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
        'bound_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'disputed_at' => 'datetime',
        'fulfilled_at' => 'datetime',
        'expired_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(PromotionCampaign::class, 'campaign_id');
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(PromotionPrize::class, 'prize_id');
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(PromotionParticipation::class, 'participation_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function fulfilledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fulfilled_by');
    }

    public function events(): HasMany
    {
        return $this->hasMany(PromotionWinEvent::class, 'win_id')->orderBy('sequence');
    }
}

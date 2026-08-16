<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PromotionCampaign extends Model
{
    use HasFactory;

    protected $table = 'campaigns';

    protected $fillable = [
        'name', 'landing_headline', 'landing_text', 'rules_text', 'code', 'starts_at', 'ends_at',
        'quota_exhaustion_policy', 'is_active', 'is_public', 'public_slot', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'quota_exhaustion_policy' => \App\Enums\PromotionQuotaPolicy::class,
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function prizes(): HasMany
    {
        return $this->hasMany(PromotionPrize::class, 'campaign_id');
    }

    public function participations(): HasMany
    {
        return $this->hasMany(PromotionParticipation::class, 'campaign_id');
    }

    public function wins(): HasMany
    {
        return $this->hasMany(PromotionWin::class, 'campaign_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(PromotionTicket::class, 'campaign_id');
    }

    public function turns(): HasMany
    {
        return $this->hasMany(PromotionTurn::class, 'campaign_id');
    }

    public function spinResults(): HasMany
    {
        return $this->hasMany(PromotionSpinResult::class, 'campaign_id');
    }

    public function promotionState(): HasOne
    {
        return $this->hasOne(PromotionCampaignState::class, 'campaign_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(PromotionWinEvent::class, 'campaign_id');
    }

    public function isOpen(): bool
    {
        $now = now();

        return $this->is_active
            && (! $this->starts_at || $this->starts_at->lte($now))
            && (! $this->ends_at || $this->ends_at->gte($now));
    }
}

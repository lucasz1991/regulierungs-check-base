<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromotionCampaign extends Model
{
    use HasFactory;

    protected $table = 'campaigns';

    protected $fillable = ['name', 'code', 'starts_at', 'ends_at', 'is_active', 'created_by'];

    protected $casts = [
        'is_active' => 'boolean',
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

<?php

namespace App\Models;

use App\Enums\PromotionFulfillmentMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromotionPrize extends Model
{
    use HasFactory;

    protected $table = 'prizes';

    protected $fillable = [
        'campaign_id', 'code', 'name', 'fulfillment_mode', 'quota',
        'reserved_count', 'is_active', 'sort_order', 'configuration',
    ];

    protected $casts = [
        'fulfillment_mode' => PromotionFulfillmentMode::class,
        'quota' => 'integer',
        'reserved_count' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'configuration' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(PromotionCampaign::class, 'campaign_id');
    }

    public function wins(): HasMany
    {
        return $this->hasMany(PromotionWin::class, 'prize_id');
    }
}

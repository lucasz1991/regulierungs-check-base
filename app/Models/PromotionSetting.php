<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionSetting extends Model
{
    protected $table = 'promotion_settings';

    protected $guarded = [];

    protected $hidden = [
        'audit_secret_encrypted',
        'configuration_mac',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'qr_ttl_minutes' => 'integer',
    ];

    public function publicCampaign(): BelongsTo
    {
        return $this->belongsTo(PromotionCampaign::class, 'public_campaign_id');
    }
}

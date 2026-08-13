<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionAuditHead extends Model
{
    protected $table = 'promotion_audit_heads';
    protected $primaryKey = 'campaign_id';
    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'last_sequence' => 'integer',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(PromotionCampaign::class, 'campaign_id');
    }
}

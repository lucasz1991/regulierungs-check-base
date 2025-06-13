<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\InsuranceSubType;

class DetailInsuranceRating extends Model
{
    protected $fillable = [
        'insurance_id',
        'insurance_subtype_id',
        'type',
        'status',
        'fairness',
        'speed',
        'communication',
        'transparency',
        'total_score',
        'ai_comment',
        'ai_tags',
        'admin_comment',
    ];

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }

    public function insuranceSubtype(): BelongsTo
    {
        return $this->belongsTo(InsuranceSubtype::class);
    }
}

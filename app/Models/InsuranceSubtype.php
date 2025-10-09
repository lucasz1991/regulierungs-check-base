<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RatingQuestion;
use App\Models\RatingQuestionnaireVersion;
use App\Models\InsuranceType;
use App\Models\Insurance;
use App\Models\ClaimRating;

class InsuranceSubtype extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'style',
        'weight',
        'average_rating_speed',
        'average_rating_fairness',
        'average_rating_service',
        'is_active',
        'allow_third_party',
        'order_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'allow_third_party' => 'boolean',
        'style' => 'array',
    ];

    public function insuranceTypes()
    {
        return $this->belongsToMany(InsuranceType::class)
        ->withPivot('order_id')
        ->orderBy('pivot_order_id');
    }

    public function insurances()
    {
        return Insurance::whereHas('insuranceTypes', function ($query) {
            $query->whereHas('subtypes', function ($subQuery) {
                $subQuery->where('insurance_subtypes.id', $this->id);
            });
        });
    }

    public function ratingQuestions()
    {
        return $this->belongsToMany(RatingQuestion::class)
                    ->withPivot('order_column', 'notes', 'visibility_conditions')
                    ->orderBy('insurance_subtype_rating_question.order_column');
    }
    public function claimRatings()
    {
        return $this->hasMany(ClaimRating::class);
    }

    public function publishedClaimRatings()
    {
        return $this->hasMany(ClaimRating::class)
            ->where('status', 'rated')
            ->where('is_public', true);
    }

    public function ratings_count()
    {
        return $this->hasMany(ClaimRating::class)->count();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function latestVersion()
    {
        return $this->hasOne(RatingQuestionnaireVersion::class)->latestOfMany('version_number');
    }
}

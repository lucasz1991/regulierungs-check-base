<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InsuranceType;
use App\Models\InsuranceSubType;
use App\Models\DetailInsuranceRating;

class Insurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'style',
        'initials',
        'color',
        'logo',
        'is_active',
        'order_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'style' => 'array',
        'order_id' => 'integer',
    ];

    
    public function insuranceTypes()
    {
        return $this->belongsToMany(InsuranceType::class, 'insurance_insurance_type')
                    ->withPivot('order_column')
                    ->orderBy('insurance_insurance_type.order_column');
    }

    public function insuranceSubTypes()
    {
        return $this->belongsToMany(InsuranceSubType::class, 'insurance_insurance_type')
            ->withPivot('order_column');
    }



    public function subtypes()
    {
        return $this->hasManyThrough(
            InsuranceSubType::class,
            ClaimRating::class,
            'insurance_id',
            'id',
            'id',
            'insurance_subtype_id'
        )->distinct();
    }

    public function detailInsuranceRatings()
    {
        return $this->hasMany(DetailInsuranceRating::class);
    }

    public function latestDetailInsuranceRating()
    {
        return $this->hasOne(DetailInsuranceRating::class)
                    ->latestOfMany();
    }

    public function latestDetailInsuranceRatingBySubtype(?int $subtypeId = null)
    {
        return $this->detailInsuranceRatings()
            ->when($subtypeId, function ($query) use ($subtypeId) {
                $query->where('insurance_subtype_id', $subtypeId);
            }, function ($query) {
                $query->whereNull('insurance_subtype_id'); // Allgemein
            })
            ->latest('created_at') // oder 'id' falls das Sortierkriterium anders ist
            ->first();
    }

    public function avgRatingDurationBySubtype(?int $subtypeId = null)
    {
        return round(
            $this->claimRatings()
                ->when($subtypeId, function ($query) use ($subtypeId) {
                    $query->where('insurance_subtype_id', $subtypeId);
                })
                ->get()
                ->map(function ($rating) {
                    return $rating->ratingDuration(); // Muss in ClaimRating definiert sein
                })
                ->filter()
                ->avg(),
            1
        );
    }

    public function claimRatingsCountBySubtype(?int $subtypeId = null)
    {
        return $this->claimRatings()
            ->when(!is_null($subtypeId), function ($query) use ($subtypeId) {
                $query->where('insurance_subtype_id', $subtypeId);
            })
            ->count();
    }

    public function avgRatingDuration()
    {
        return round(
            $this->claimRatings->map(function ($rating) {
                return $rating->ratingDuration();
            })->filter()->avg(),
            1
        );
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

    public function ratings_avg_score()
    {
        return $this->hasMany(ClaimRating::class)->avg('rating_score');
    }

    public function ratings_count()
    {
        return $this->hasMany(ClaimRating::class)
                ->count();
    }

    public function published_ratings_count()
    {
        return $this->hasMany(ClaimRating::class)
                ->where('status', 'rated')
                ->where('is_public', true)
                ->count();
    }

    public function published_claimRatingsCountBySubtype(?int $subtypeId = null)
    {
        return $this->claimRatings()
            ->when(!is_null($subtypeId), function ($query) use ($subtypeId) {
                $query->where('insurance_subtype_id', $subtypeId);
            })
            ->where('status', 'rated')
            ->where('is_public', true)
            ->count();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}

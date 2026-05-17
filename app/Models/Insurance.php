<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use App\Models\InsuranceType;
use App\Models\InsuranceSubtype;
use App\Models\DetailInsuranceRating;

class Insurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'helptext',
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
        return $this->belongsToMany(InsuranceSubtype::class, 'insurance_insurance_type')
            ->withPivot('order_column');
    }



    public function subtypes()
    {
        return $this->hasManyThrough(
            InsuranceSubtype::class,
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
        return $this->latestDetailInsuranceRatingByTypeAndSubtype(null, $subtypeId);
    }

    public function latestDetailInsuranceRatingByTypeAndSubtype(?int $typeId = null, ?int $subtypeId = null)
    {
        $query = $this->detailInsuranceRatings();

        if ($this->detailInsuranceRatingsHaveInsuranceTypeColumn()) {
            $query->when(!is_null($typeId), function ($query) use ($typeId) {
                $query->where('insurance_type_id', $typeId);
            }, function ($query) {
                $query->whereNull('insurance_type_id');
            });
        }

        return $query->when(!is_null($subtypeId), function ($query) use ($subtypeId) {
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

    public function avgRatingDurationBySubtypeIds(array $subtypeIds = [])
    {
        $subtypeIds = collect($subtypeIds)
            ->filter(fn ($id) => !is_null($id) && $id !== '')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        return round(
            $this->claimRatings()
                ->when(!empty($subtypeIds), function ($query) use ($subtypeIds) {
                    $query->whereIn('insurance_subtype_id', $subtypeIds);
                })
                ->publiclyVisible()
                ->get()
                ->map(function ($rating) {
                    return $rating->ratingDuration();
                })
                ->filter()
                ->avg(),
            1
        );
    }

    public function avgRatingDurationByTypeAndSubtypeIds(array $typeIds = [], array $typeSubtypeIds = [], array $subtypeIds = [])
    {
        return round(
            $this->filteredPublishedClaimRatings($typeIds, $typeSubtypeIds, $subtypeIds)
                ->get()
                ->map(function ($rating) {
                    return $rating->ratingDuration();
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
            ->publiclyVisible();
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
                ->publiclyVisible()
                ->count();
    }

    public function published_claimRatingsCountBySubtype(?int $subtypeId = null)
    {
        return $this->claimRatings()
            ->when(!is_null($subtypeId), function ($query) use ($subtypeId) {
                $query->where('insurance_subtype_id', $subtypeId);
            })
            ->publiclyVisible()
            ->count();
    }

    public function published_claimRatingsCountBySubtypeIds(array $subtypeIds = [])
    {
        $subtypeIds = collect($subtypeIds)
            ->filter(fn ($id) => !is_null($id) && $id !== '')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        return $this->claimRatings()
            ->when(!empty($subtypeIds), function ($query) use ($subtypeIds) {
                $query->whereIn('insurance_subtype_id', $subtypeIds);
            })
            ->publiclyVisible()
            ->count();
    }

    public function published_claimRatingsCountByTypeAndSubtypeIds(array $typeIds = [], array $typeSubtypeIds = [], array $subtypeIds = [])
    {
        return $this->filteredPublishedClaimRatings($typeIds, $typeSubtypeIds, $subtypeIds)
            ->count();
    }

    public function published_ratings_avg_scoreBySubtypeIds(array $subtypeIds = [])
    {
        $subtypeIds = collect($subtypeIds)
            ->filter(fn ($id) => !is_null($id) && $id !== '')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        return $this->claimRatings()
            ->when(!empty($subtypeIds), function ($query) use ($subtypeIds) {
                $query->whereIn('insurance_subtype_id', $subtypeIds);
            })
            ->publiclyVisible()
            ->avg('rating_score');
    }

    public function published_ratings_avg_scoreByTypeAndSubtypeIds(array $typeIds = [], array $typeSubtypeIds = [], array $subtypeIds = [])
    {
        return $this->filteredPublishedClaimRatings($typeIds, $typeSubtypeIds, $subtypeIds)
            ->avg('rating_score');
    }

    public function publishedClaimRatingDistributionBySubtype(?int $subtypeId = null): array
    {
        $stats = $this->claimRatings()
            ->when(!is_null($subtypeId), function ($query) use ($subtypeId) {
                $query->where('insurance_subtype_id', $subtypeId);
            })
            ->publiclyVisible()
            ->selectRaw('
                COUNT(*) as total_count,
                SUM(CASE WHEN rating_score >= 0.7 THEN 1 ELSE 0 END) as good_count,
                SUM(CASE WHEN rating_score >= 0.4 AND rating_score < 0.7 THEN 1 ELSE 0 END) as sufficient_count,
                SUM(CASE WHEN rating_score < 0.4 THEN 1 ELSE 0 END) as very_bad_count
            ')
            ->first();

        $total = (int) ($stats->total_count ?? 0);
        $good = (int) ($stats->good_count ?? 0);
        $sufficient = (int) ($stats->sufficient_count ?? 0);
        $veryBad = (int) ($stats->very_bad_count ?? 0);
        $other = max(0, $total - ($good + $sufficient + $veryBad));

        return [
            'total' => $total,
            'good' => $good,
            'sufficient' => $sufficient,
            'very_bad' => $veryBad,
            'other' => $other,
        ];
    }

    public function publishedClaimRatingRegulationTypeDistributionBySubtype(?int $subtypeId = null): array
    {
        $counts = [
            'total' => 0,
            'teilzahlung' => 0,
            'vollzahlung' => 0,
            'ablehnung' => 0,
            'austehend' => 0,
            'other' => 0,
        ];

        $this->claimRatings()
            ->when(!is_null($subtypeId), function ($query) use ($subtypeId) {
                $query->where('insurance_subtype_id', $subtypeId);
            })
            ->publiclyVisible()
            ->get(['answers'])
            ->each(function (ClaimRating $rating) use (&$counts) {
                $regulationType = strtolower((string) data_get($rating->answers, 'regulationType', ''));

                $counts['total']++;

                if (array_key_exists($regulationType, $counts) && !in_array($regulationType, ['total', 'other'], true)) {
                    $counts[$regulationType]++;
                    return;
                }

                $counts['other']++;
            });

        return $counts;
    }

    public function publishedClaimRatingRegulationTypeDistributionByTypeAndSubtypeIds(array $typeIds = [], array $typeSubtypeIds = [], array $subtypeIds = []): array
    {
        $counts = [
            'total' => 0,
            'teilzahlung' => 0,
            'vollzahlung' => 0,
            'ablehnung' => 0,
            'austehend' => 0,
            'other' => 0,
        ];

        $this->filteredPublishedClaimRatings($typeIds, $typeSubtypeIds, $subtypeIds)
            ->get(['answers'])
            ->each(function (ClaimRating $rating) use (&$counts) {
                $regulationType = strtolower((string) data_get($rating->answers, 'regulationType', ''));

                $counts['total']++;

                if (array_key_exists($regulationType, $counts) && !in_array($regulationType, ['total', 'other'], true)) {
                    $counts[$regulationType]++;
                    return;
                }

                $counts['other']++;
            });

        return $counts;
    }

    public function detailInsuranceRatingByTypeAndSubtypeIds(array $typeIds = [], array $typeSubtypeIds = [], array $subtypeIds = []): ?DetailInsuranceRating
    {
        $typeIds = $this->normalizeFilterIds($typeIds);
        $typeSubtypeIds = $this->normalizeFilterIds($typeSubtypeIds);
        $subtypeIds = $this->normalizeFilterIds($subtypeIds);

        if (empty($typeIds) && empty($typeSubtypeIds) && empty($subtypeIds)) {
            return $this->latestDetailInsuranceRatingByTypeAndSubtype();
        }

        $hasExplicitSubtypeFilter = !empty($subtypeIds);
        $detailSubtypeIds = $hasExplicitSubtypeFilter ? $subtypeIds : $typeSubtypeIds;
        $hasTypeColumn = $this->detailInsuranceRatingsHaveInsuranceTypeColumn();

        if ($hasTypeColumn && !empty($typeIds) && !$hasExplicitSubtypeFilter) {
            if (count($typeIds) === 1) {
                $rating = $this->latestDetailInsuranceRatingByTypeAndSubtype($typeIds[0]);

                if ($rating) {
                    return $rating;
                }
            }

            $typeAggregate = $this->aggregateDetailInsuranceRatings(
                $this->detailInsuranceRatings()
                    ->whereIn('insurance_type_id', $typeIds)
                    ->whereNull('insurance_subtype_id')
            );

            if ($typeAggregate) {
                return $typeAggregate;
            }
        }

        if ($hasTypeColumn && count($typeIds) === 1 && count($detailSubtypeIds) === 1) {
            $rating = $this->latestDetailInsuranceRatingByTypeAndSubtype($typeIds[0], $detailSubtypeIds[0]);

            if ($rating) {
                return $rating;
            }
        }

        if (!empty($typeIds) && count($detailSubtypeIds) === 1) {
            $rating = $this->latestDetailInsuranceRatingBySubtype($detailSubtypeIds[0]);

            if ($rating) {
                return $rating;
            }
        }

        if (empty($typeIds) && count($detailSubtypeIds) === 1) {
            $rating = $this->latestDetailInsuranceRatingBySubtype($detailSubtypeIds[0]);

            if ($rating) {
                return $rating;
            }
        }

        if (empty($detailSubtypeIds)) {
            return null;
        }

        $query = $this->detailInsuranceRatings()
            ->whereIn('insurance_subtype_id', $detailSubtypeIds);

        if ($hasTypeColumn) {
            if (!empty($typeIds)) {
                $query->whereIn('insurance_type_id', $typeIds);
            } else {
                $query->whereNull('insurance_type_id');
            }
        }

        $aggregate = $this->aggregateDetailInsuranceRatings($query);

        if ($aggregate) {
            return $aggregate;
        }

        if ($hasTypeColumn && !empty($typeIds) && !$hasExplicitSubtypeFilter) {
            return $this->aggregateDetailInsuranceRatings(
                $this->detailInsuranceRatings()
                    ->whereNull('insurance_type_id')
                    ->whereIn('insurance_subtype_id', $detailSubtypeIds)
            );
        }

        if ($hasTypeColumn && !empty($typeIds) && $hasExplicitSubtypeFilter) {
            return $this->aggregateDetailInsuranceRatings(
                $this->detailInsuranceRatings()
                    ->whereNull('insurance_type_id')
                    ->whereIn('insurance_subtype_id', $detailSubtypeIds)
            );
        }

        if (empty($typeIds)) {
            return $this->aggregateDetailInsuranceRatings(
                $this->detailInsuranceRatings()
                    ->whereIn('insurance_subtype_id', $detailSubtypeIds)
            );
        }

        return null;
    }

    private function detailInsuranceRatingsHaveInsuranceTypeColumn(): bool
    {
        static $hasColumn = null;

        return $hasColumn ??= Schema::hasColumn('detail_insurance_ratings', 'insurance_type_id');
    }

    private function aggregateDetailInsuranceRatings($query): ?DetailInsuranceRating
    {
        $stats = $query->selectRaw('
            COUNT(*) as detail_count,
            AVG(speed) as speed,
            AVG(communication) as communication,
            AVG(fairness) as fairness,
            AVG(transparency) as transparency,
            AVG(total_score) as total_score
        ')->first();

        if ((int) ($stats->detail_count ?? 0) <= 0) {
            return null;
        }

        return new DetailInsuranceRating([
            'insurance_id' => $this->id,
            'insurance_type_id' => null,
            'insurance_subtype_id' => null,
            'type' => 'aggregated',
            'status' => 'published',
            'speed' => $stats->speed,
            'communication' => $stats->communication,
            'fairness' => $stats->fairness,
            'transparency' => $stats->transparency,
            'total_score' => $stats->total_score,
            'ai_comment' => null,
        ]);
    }

    public function published_claimRatings_avgRatingDurationBySubtype(?int $subtypeId = null)
    {
return null;
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }

    private function filteredPublishedClaimRatings(array $typeIds = [], array $typeSubtypeIds = [], array $subtypeIds = []): HasMany
    {
        $typeIds = $this->normalizeFilterIds($typeIds);
        $typeSubtypeIds = $this->normalizeFilterIds($typeSubtypeIds);
        $subtypeIds = $this->normalizeFilterIds($subtypeIds);

        return $this->claimRatings()
            ->publiclyVisible()
            ->when(!empty($typeIds) || !empty($typeSubtypeIds), function ($query) use ($typeIds, $typeSubtypeIds) {
                $query->where(function ($typeQuery) use ($typeIds, $typeSubtypeIds) {
                    if (!empty($typeIds)) {
                        $typeQuery->whereIn('insurance_type_id', $typeIds);
                    }

                    if (!empty($typeSubtypeIds)) {
                        if (!empty($typeIds)) {
                            $typeQuery->orWhereIn('insurance_subtype_id', $typeSubtypeIds);
                        } else {
                            $typeQuery->whereIn('insurance_subtype_id', $typeSubtypeIds);
                        }
                    }
                });
            })
            ->when(!empty($subtypeIds), function ($query) use ($subtypeIds) {
                $query->whereIn('insurance_subtype_id', $subtypeIds);
            });
    }

    private function normalizeFilterIds(array $ids): array
    {
        return collect($ids)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

}

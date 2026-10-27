<?php

namespace App\Livewire\Banner;

use App\Models\ClaimRating;
use App\Models\Insurance;
use App\Models\InsuranceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class TopInsurancesByTypeBanner extends Component
{
    public Collection $types;

    public Collection $insurances;

    public ?int $selectedInsuranceTypeId = null;

    public array $selectedSubtypeIds = [];

    public bool $showAllInsuranceTypes = false;

    public int $limit = 20;

    public int $minPublishedCount = 1;

    private bool $insurancesLoaded = false;

    public function mount(): void
    {
        $this->loadTypes();
        $this->loadInsurances();
    }

    public function selectInsuranceType(?int $typeId = null): void
    {
        $this->selectedInsuranceTypeId = $typeId ?: null;
        $this->loadInsurances();
    }

    public function clearInsuranceTypeFilter(): void
    {
        $this->selectedInsuranceTypeId = null;
        $this->loadInsurances();
    }

    protected function loadTypes(): void
    {
        $publishedRatings = ClaimRating::query()
            ->join('insurance_type_insurance_subtype as itis', 'itis.insurance_subtype_id', '=', 'claim_ratings.insurance_subtype_id')
            ->join('insurances as i', 'i.id', '=', 'claim_ratings.insurance_id')
            ->whereColumn('itis.insurance_type_id', 'insurance_types.id')
            ->where('i.is_active', true)
            ->where('claim_ratings.is_public', true)
            ->whereIn('claim_ratings.status', ClaimRating::publicVisibleStatuses());

        $query = InsuranceType::query()
            ->where('insurance_types.is_active', true)
            ->addSelect([
                'published_ratings_count' => (clone $publishedRatings)->selectRaw('COUNT(*)'),
            ])
            ->whereExists((clone $publishedRatings)->selectRaw('1'))
            ->orderByDesc('published_ratings_count');

        if (Schema::hasColumn('insurance_types', 'order_id')) {
            $query->orderByRaw('CASE WHEN order_id IS NULL THEN 1 ELSE 0 END')
                ->orderBy('order_id');
        } elseif (Schema::hasColumn('insurance_types', 'order_column')) {
            $query->orderByRaw('CASE WHEN order_column IS NULL THEN 1 ELSE 0 END')
                ->orderBy('order_column');
        }

        $this->types = $query
            ->orderBy('name')
            ->get();
    }

    protected function loadInsurances(): void
    {
        $selectedTypeId = $this->selectedInsuranceTypeId;
        $this->selectedSubtypeIds = [];

        if ($selectedTypeId) {
            $this->selectedSubtypeIds = InsuranceType::query()
                ->whereKey($selectedTypeId)
                ->first()
                ?->subtypes()
                ->pluck('insurance_subtypes.id')
                ->map(fn ($id) => (int) $id)
                ->all() ?? [];
        }

        $selectedSubtypeIds = $this->selectedSubtypeIds;

        $this->insurances = Insurance::query()
            ->where('is_active', true)
            ->whereHas('claimRatings', function ($query) use ($selectedTypeId, $selectedSubtypeIds) {
                $this->applyRatingFilter($query, $selectedTypeId, $selectedSubtypeIds);
            }, '>=', max(1, $this->minPublishedCount))
            ->with(['claimRatings' => function (HasMany $query) use ($selectedTypeId, $selectedSubtypeIds) {
                $this->applyRatingFilter($query->getQuery(), $selectedTypeId, $selectedSubtypeIds);
                $query->select(['id', 'insurance_id', 'answers']);
            }])
            ->withCount([
                'claimRatings as published_count' => function ($query) use ($selectedTypeId, $selectedSubtypeIds) {
                    $this->applyRatingFilter($query, $selectedTypeId, $selectedSubtypeIds);
                },
            ])
            ->withAvg([
                'claimRatings as published_avg' => function ($query) use ($selectedTypeId, $selectedSubtypeIds) {
                    $this->applyRatingFilter($query, $selectedTypeId, $selectedSubtypeIds);
                },
            ], 'rating_score')
            ->orderByDesc('published_count')
            ->orderByDesc('published_avg')
            ->orderBy('insurances.id')
            ->take($this->limit)
            ->get();

        $this->insurancesLoaded = true;
    }

    private function applyRatingFilter(Builder $query, ?int $typeId, array $subtypeIds): void
    {
        $query->publiclyVisible();

        if (! $typeId) {
            return;
        }

        $query->where(function (Builder $filterQuery) use ($typeId, $subtypeIds) {
            $filterQuery->where('insurance_type_id', $typeId);

            if (! empty($subtypeIds)) {
                $filterQuery->orWhereIn('insurance_subtype_id', $subtypeIds);
            }
        });
    }

    public function render()
    {
        // Livewire restores model identities, not aggregate columns or the
        // constraints on an eager-loaded relation. Rebuild these server-side
        // after hydration so a refresh cannot read unfiltered claim ratings.
        if (! $this->insurancesLoaded) {
            $this->loadTypes();
            $this->loadInsurances();
        }

        return view('livewire.banner.top-insurances-by-type-banner');
    }
}

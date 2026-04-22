<?php

namespace App\Livewire\Insurance;

use App\Models\ClaimRating;
use App\Models\Insurance;
use App\Models\InsuranceSubtype;
use App\Models\InsuranceType;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InsuranceList extends Component
{
    public string $search = '';

    /** IMPORTANT: muss Collection sein (dein Dropdown macht $options->pluck()) */
    public Collection $insuranceTypes;
    public Collection $insuranceSubTypes;

    public $selectedInsuranceTypefilter = [];
    public $selectedInsuranceSubTypefilter = [];

    public int $perPage = 20;
    public int $pages   = 1;

    public string $sort = 'count_desc';
    public int $minRatingCount = 1;
    public ?float $minAvgScore = null;

    public bool $onlyActive = false;

    public bool $isSubTypeFilter = false;
    public ?InsuranceSubtype $subTypeFilterSubType = null;

    protected $queryString = [
        'selectedInsuranceTypefilter' => ['as' => 'types', 'except' => []],
        'selectedInsuranceSubTypefilter' => ['as' => 'subtypes', 'except' => []],
        'search' => ['as' => 'q', 'except' => ''],
        'minRatingCount' => ['as' => 'min_count', 'except' => 1],
        'minAvgScore' => ['as' => 'min_score', 'except' => null],
        'sort' => ['except' => 'count_desc'],
        'onlyActive' => ['as' => 'active', 'except' => false],
    ];

    public function mount(): void
    {
        $this->selectedInsuranceTypefilter = $this->normalizeFilterIds($this->selectedInsuranceTypefilter);
        $this->selectedInsuranceSubTypefilter = $this->normalizeFilterIds($this->selectedInsuranceSubTypefilter);
        $this->insuranceTypes = InsuranceType::query()
            ->where('insurance_types.is_active', true)
            ->where(function (Builder $query) {
                $query->whereIn('id', ClaimRating::query()
                    ->select('insurance_type_id')
                    ->whereNotNull('insurance_type_id')
                    ->publiclyVisible()
                    ->distinct()
                )->orWhereHas('subtypes.publishedClaimRatings', fn (Builder $q) => $q->publiclyVisible());
            })
            ->orderBy('name')
            ->get();

        $this->insuranceSubTypes = InsuranceSubtype::query()
            ->whereHas('claimRatings', fn (Builder $q) => $q->publiclyVisible())
            ->orderBy('name')
            ->get();

        $this->pages = 1;
        $this->syncSubtypeState($this->selectedInsuranceSubTypefilter);
    }

    /** Load-More: bei jeder Änderung wieder auf Seite 1 */
    public function updatingSearch(): void { $this->pages = 1; }
    public function updatingSort(): void { $this->pages = 1; }
    public function updatingMinRatingCount(): void { $this->pages = 1; }
    public function updatingMinAvgScore(): void { $this->pages = 1; }
    public function updatingOnlyActive(): void { $this->pages = 1; }

    public function updatingSelectedInsuranceTypefilter($value): void
    {
        $this->pages = 1;
        $this->selectedInsuranceTypefilter = $this->normalizeFilterIds($value);
    }

    public function updatingSelectedInsuranceSubTypefilter($value): void
    {
        $this->pages = 1;

        $value = $this->normalizeFilterIds($value);
        $this->selectedInsuranceSubTypefilter = $value;

        $this->syncSubtypeState($value);
    }

    public function loadMore(): void
    {
        $this->pages++;
    }

    public function resetFilters(): void
    {
        $this->reset([
            'selectedInsuranceTypefilter',
            'selectedInsuranceSubTypefilter',
            'search',
            'minRatingCount',
            'minAvgScore',
            'onlyActive',
            'sort',
        ]);

        $this->pages = 1;
        $this->syncSubtypeState($this->selectedInsuranceSubTypefilter);
    }

    public function getIsFilteredProperty(): bool
    {
        return !empty($this->selectedInsuranceTypefilter)
            || !empty($this->selectedInsuranceSubTypefilter)
            || filled($this->search)
            || !is_null($this->minAvgScore)
            || ($this->minRatingCount !== 1)
            || ($this->onlyActive === true);
    }

    public function render(): View
    {
        $query = $this->baseQuery();

        // Sort
$query = match ($this->sort) {
    'score_desc' => $query
        ->orderByDesc('claim_ratings_avg_rating_score')
        ->orderByDesc('claim_ratings_count')
        ->orderBy('insurances.id'),

    'score_asc' => $query
        ->orderBy('claim_ratings_avg_rating_score')
        ->orderByDesc('claim_ratings_count')
        ->orderBy('insurances.id'),

    'count_asc' => $query
        ->orderBy('claim_ratings_count')
        ->orderByDesc('claim_ratings_avg_rating_score')
        ->orderBy('insurances.id'),

    default => $query
        ->orderByDesc('claim_ratings_count')
        ->orderByDesc('claim_ratings_avg_rating_score')
        ->orderBy('insurances.id'),
};

        // Total (optional, für "X gefunden")
        $total = (clone $query)->toBase()->getCountForPagination();

        // Load more (nur limit erhöhen)
        $insurances = $query
            ->limit($this->perPage * $this->pages)
            ->get();

        return view('livewire.insurance.insurance-list', [
            'insurances'           => $insurances,
            'total'                => $total,
            'insuranceTypes'       => $this->insuranceTypes,
            'insuranceSubTypes'    => $this->insuranceSubTypes,
            'isSubTypeFilter'      => $this->isSubTypeFilter,
            'subTypeFilterSubType' => $this->subTypeFilterSubType,
            'selectedInsuranceTypeIds' => $this->selectedInsuranceTypeIds(),
            'selectedInsuranceSubTypeIds' => $this->selectedInsuranceSubtypeIds(),
            'selectedInsuranceTypeSubtypeIds' => $this->selectedInsuranceTypeSubtypeIds($this->selectedInsuranceTypeIds()),
        ]);
    }

    private function baseQuery(): Builder
    {
        $typeIds = $this->selectedInsuranceTypeIds();
        $typeSubtypeIds = $this->selectedInsuranceTypeSubtypeIds($typeIds);
        $subtypeIds = $this->selectedInsuranceSubtypeIds();

        $query = Insurance::query()
            ->withCount(['claimRatings as claim_ratings_count' => function (Builder $q) use ($typeIds, $typeSubtypeIds, $subtypeIds) {
                $q->publiclyVisible();
                $this->applyRatingFilters($q, $typeIds, $typeSubtypeIds, $subtypeIds);
            }])
            ->withAvg(['claimRatings as claim_ratings_avg_rating_score' => function (Builder $q) use ($typeIds, $typeSubtypeIds, $subtypeIds) {
                $q->publiclyVisible();
                $this->applyRatingFilters($q, $typeIds, $typeSubtypeIds, $subtypeIds);
            }], 'rating_score')
            ->whereHas('claimRatings', function (Builder $q) use ($typeIds, $typeSubtypeIds, $subtypeIds) {
                $q->publiclyVisible();
                $this->applyRatingFilters($q, $typeIds, $typeSubtypeIds, $subtypeIds);
            })
            ->when(filled($this->search), fn (Builder $q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->minRatingCount, fn (Builder $q) => $q->having('claim_ratings_count', '>=', $this->minRatingCount))
            ->when(!is_null($this->minAvgScore), fn (Builder $q) => $q->having('claim_ratings_avg_rating_score', '>=', $this->minAvgScore));

        // onlyActive (optional) – nur wenn du ein Feld hast (z.B. active/is_active)
        // if ($this->onlyActive) $query->where('is_active', true);

        return $query;
    }

    private function syncSubtypeState($value): void
    {
        $value = $this->normalizeFilterIds($value);

        if (count($value) === 1) {
            $this->isSubTypeFilter = true;
            $this->subTypeFilterSubType = InsuranceSubtype::find((int) $value[0]);
        } else {
            $this->isSubTypeFilter = false;
            $this->subTypeFilterSubType = null;
        }
    }

    private function applyRatingFilters(Builder $query, array $typeIds, array $typeSubtypeIds, array $subtypeIds): void
    {
        if (!empty($typeIds) || !empty($typeSubtypeIds)) {
            $query->where(function (Builder $typeQuery) use ($typeIds, $typeSubtypeIds) {
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
        }

        if (!empty($subtypeIds)) {
            $query->whereIn('insurance_subtype_id', $subtypeIds);
        }
    }

    private function selectedInsuranceTypeIds(): array
    {
        return $this->normalizeFilterIds($this->selectedInsuranceTypefilter);
    }

    private function selectedInsuranceSubtypeIds(): array
    {
        return $this->normalizeFilterIds($this->selectedInsuranceSubTypefilter);
    }

    private function selectedInsuranceTypeSubtypeIds(array $selectedInsuranceTypeIds): array
    {
        if (empty($selectedInsuranceTypeIds)) {
            return [];
        }

        return DB::table('insurance_type_insurance_subtype')
            ->whereIn('insurance_type_id', $selectedInsuranceTypeIds)
            ->pluck('insurance_subtype_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    private function normalizeFilterIds($value): array
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        $values = is_array($value) ? $value : [$value];

        return collect($values)
            ->flatMap(fn ($id) => is_string($id) ? explode(',', $id) : [$id])
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }
}

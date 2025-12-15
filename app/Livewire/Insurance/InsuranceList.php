<?php

namespace App\Livewire\Insurance;

use App\Models\Insurance;
use App\Models\InsuranceSubtype;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Session;
use Livewire\Component;

class InsuranceList extends Component
{
    public string $search = '';

    /** IMPORTANT: muss Collection sein (dein Dropdown macht $options->pluck()) */
    public Collection $insuranceSubTypes;

    #[Session(key: 'selectedInsuranceSubTypefilter')]
    public array $selectedInsuranceSubTypefilter = [];

    public int $perPage = 10;
    public int $pages   = 1;

    public string $sort = 'count_desc';
    public int $minRatingCount = 1;
    public ?float $minAvgScore = null;

    public bool $onlyActive = false;

    public bool $isSubTypeFilter = false;
    public ?InsuranceSubtype $subTypeFilterSubType = null;

    public function mount(): void
    {
        $this->insuranceSubTypes = InsuranceSubtype::query()
            ->whereHas('claimRatings', fn (Builder $q) => $q->where('status', 'rated')->where('is_public', true))
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

    public function updatingSelectedInsuranceSubTypefilter($value): void
    {
        $this->pages = 1;

        $value = is_array($value) ? $value : [];
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
            'selectedInsuranceSubTypefilter',
            'search',
            'minRatingCount',
            'minAvgScore',
            'onlyActive',
        ]);

        $this->pages = 1;
        $this->syncSubtypeState($this->selectedInsuranceSubTypefilter);
    }

    public function getIsFilteredProperty(): bool
    {
        return !empty($this->selectedInsuranceSubTypefilter)
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
            'insuranceSubTypes'    => $this->insuranceSubTypes,
            'isSubTypeFilter'      => $this->isSubTypeFilter,
            'subTypeFilterSubType' => $this->subTypeFilterSubType,
        ]);
    }

    private function baseQuery(): Builder
    {
        $ids = array_values(array_filter($this->selectedInsuranceSubTypefilter, fn ($v) => is_numeric($v)));

        $query = Insurance::query()
            ->withCount(['claimRatings as claim_ratings_count' => function (Builder $q) {
                $q->where('status', 'rated')->where('is_public', true);
            }])
            ->withAvg(['claimRatings as claim_ratings_avg_rating_score' => function (Builder $q) {
                $q->where('status', 'rated')->where('is_public', true);
            }], 'rating_score')
            ->whereHas('claimRatings', fn (Builder $q) => $q->where('status', 'rated')->where('is_public', true))
            ->when(filled($this->search), fn (Builder $q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when(!empty($ids), function (Builder $q) use ($ids) {
                $q->whereHas('claimRatings', function (Builder $sub) use ($ids) {
                    $sub->where('status', 'rated')
                        ->where('is_public', true)
                        ->whereIn('insurance_subtype_id', $ids);
                });
            })
            ->when($this->minRatingCount, fn (Builder $q) => $q->having('claim_ratings_count', '>=', $this->minRatingCount))
            ->when(!is_null($this->minAvgScore), fn (Builder $q) => $q->having('claim_ratings_avg_rating_score', '>=', $this->minAvgScore));

        // onlyActive (optional) – nur wenn du ein Feld hast (z.B. active/is_active)
        // if ($this->onlyActive) $query->where('is_active', true);

        return $query;
    }

    private function syncSubtypeState(array $value): void
    {
        $value = array_values(array_filter($value, fn ($v) => is_numeric($v)));

        if (count($value) === 1) {
            $this->isSubTypeFilter = true;
            $this->subTypeFilterSubType = InsuranceSubtype::find((int) $value[0]);
        } else {
            $this->isSubTypeFilter = false;
            $this->subTypeFilterSubType = null;
        }
    }
}

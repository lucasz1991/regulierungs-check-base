<?php

namespace App\Livewire\Pages;

use App\Models\ClaimRating;
use App\Models\Insurance;
use App\Models\InsuranceSubtype;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Livewire\Component;

class Ranking extends Component
{
    private const ASPECTS = [
        'allgemein' => ['label' => 'Allgemein', 'column' => 'rating_score'],
        'geschwindigkeit' => ['label' => 'Geschwindigkeit', 'column' => 'attachments->scorings->regulation_speed'],
        'kunden_service' => ['label' => 'Kunden Service', 'column' => 'attachments->scorings->customer_service'],
        'fairness' => ['label' => 'Fairness', 'column' => 'attachments->scorings->fairness'],
        'transparenz' => ['label' => 'Transparenz', 'column' => 'attachments->scorings->transparency'],
    ];

    public Collection $insuranceSubTypes;

    public array $selectedInsuranceSubTypefilter = [];

    public string $selectedAspect = 'allgemein';

    public int $visibleCount = 20;

    protected $listeners = ['refreshRanking' => '$refresh'];

    public function mount(): void
    {
        $this->insuranceSubTypes = InsuranceSubtype::query()
            ->whereHas('publishedClaimRatings')
            ->orderBy('name')
            ->get();
    }

    public function updatedSelectedInsuranceSubTypefilter(): void
    {
        $this->visibleCount = 20;
        $this->selectedInsuranceSubTypefilter = $this->selectedSubtypeIds();
    }

    public function updatedSelectedAspect(): void
    {
        $this->visibleCount = 20;
        if (! array_key_exists($this->selectedAspect, self::ASPECTS)) {
            $this->selectedAspect = 'allgemein';
        }
    }

    public function resetFilters(): void
    {
        $this->reset('selectedInsuranceSubTypefilter', 'selectedAspect', 'visibleCount');
    }

    public function loadMore(): void
    {
        $this->visibleCount += 20;
    }

    public function render(): View
    {
        $subtypeIds = $this->selectedSubtypeIds();
        $aspect = self::ASPECTS[$this->selectedAspect] ?? self::ASPECTS['allgemein'];
        $rankedInsurances = $this->rankedInsurances($aspect['column'], $subtypeIds);
        $visibleCount = max(20, $this->visibleCount);

        return view('livewire.pages.ranking', [
            'allInsurances' => $rankedInsurances->take($visibleCount),
            'totalInsurances' => $rankedInsurances->count(),
            'hasMoreInsurances' => $rankedInsurances->count() > $visibleCount,
            'selectedSubtypeIds' => $subtypeIds,
            'aspectLabel' => $aspect['label'],
        ])->layout('layouts.app');
    }

    private function selectedSubtypeIds(): array
    {
        $availableIds = $this->insuranceSubTypes->pluck('id')->map(fn ($id) => (int) $id)->all();

        return collect($this->selectedInsuranceSubTypefilter)
            ->filter(fn ($id) => is_numeric($id) && (int) $id > 0)
            ->map(fn ($id) => (int) $id)
            ->intersect($availableIds)
            ->unique()
            ->values()
            ->all();
    }

    private function rankedInsurances(string $column, array $subtypeIds): EloquentCollection
    {
        // Use the same published-rating scope as the insurer catalogue. Aspect
        // values are the existing 0..1 scores saved by ClaimRatingAIEval, not a
        // newly inferred score or an unpublished aggregate analysis.
        $aspectKey = $column === 'rating_score' ? null : substr($column, strrpos($column, '->') + 2);
        $selectedScore = $aspectKey === null ? 'rating_score as ranking_value' : 'attachments->scorings as ranking_values';
        $ratings = ClaimRating::query()
            ->publiclyVisible()
            ->when($subtypeIds, fn ($query) => $query->whereIn('insurance_subtype_id', $subtypeIds))
            ->get(['insurance_id', $selectedScore]);

        $statistics = [];

        foreach ($ratings as $rating) {
            // Keep JSON types intact: a stored false is missing/invalid data,
            // whereas a genuine numeric zero is a valid score.
            $value = $aspectKey === null
                ? $rating->ranking_value
                : data_get(json_decode($rating->ranking_values ?? '{}', true), $aspectKey);

            if (! is_numeric($value) || (float) $value < 0 || (float) $value > 1) {
                continue;
            }

            $id = (int) $rating->insurance_id;
            $statistics[$id] ??= ['sum' => 0.0, 'count' => 0];
            $statistics[$id]['sum'] += (float) $value;
            $statistics[$id]['count']++;
        }

        return Insurance::query()
            ->whereKey(array_keys($statistics))
            ->get()
            ->each(function (Insurance $insurance) use ($statistics): void {
                $stats = $statistics[$insurance->id];
                $insurance->setAttribute('ranking_score', $stats['sum'] / $stats['count']);
                $insurance->setAttribute('ranking_count', $stats['count']);
            })
            ->sort(fn (Insurance $left, Insurance $right) => ($right->ranking_score <=> $left->ranking_score)
                ?: ($right->ranking_count <=> $left->ranking_count)
                ?: ($left->id <=> $right->id)
            )
            ->values();
    }
}

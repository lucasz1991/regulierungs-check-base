<?php

namespace App\Livewire\Insurance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Insurance;
use App\Models\ClaimRating;
use App\Models\InsuranceType;
use App\Models\InsuranceSubtype;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ShowInsurance extends Component
{
    use WithPagination;

    public Insurance $insurance;

    public $search = '';
    public $insuranceTypes = [];
    public $selectedInsuranceTypefilter = [];
    public $insuranceSubTypes = [];
    public $selectedInsuranceSubTypefilter = [];
    public $perPage = 20;
    public $pages = 1;


    public $selectedAspect = 'allgemein';
    public $sort = 'score_desc';
    public $minRatingCount = 1;
    public $minAvgScore = null;
    public $onlyActive;

    public $isSubTypeFilter;
    public $subTypeFilterSubType;

    public ?int $subTypeFilterSubTypeId = null;

    protected $queryString = [
        'selectedInsuranceTypefilter' => ['as' => 'types', 'except' => []],
        'selectedInsuranceSubTypefilter' => ['as' => 'subtypes', 'except' => []],
        'search' => ['as' => 'q', 'except' => ''],
        'minAvgScore' => ['as' => 'min_score', 'except' => null],
        'sort' => ['except' => 'score_desc'],
    ];

    public function mount(Insurance $insurance)
    {
        $this->insurance = $insurance;
        $this->selectedInsuranceTypefilter = $this->normalizeFilterIds($this->selectedInsuranceTypefilter);
        $this->selectedInsuranceSubTypefilter = $this->normalizeFilterIds($this->selectedInsuranceSubTypefilter);
        $this->insuranceTypes = InsuranceType::query()
            ->where('insurance_types.is_active', true)
            ->where(function ($query) use ($insurance) {
                $query->whereIn('id', ClaimRating::query()
                    ->select('insurance_type_id')
                    ->where('insurance_id', $insurance->id)
                    ->whereNotNull('insurance_type_id')
                    ->publiclyVisible()
                    ->distinct()
                )->orWhereHas('subtypes.publishedClaimRatings', function ($q) use ($insurance) {
                    $q->where('insurance_id', $insurance->id)
                        ->publiclyVisible();
                });
            })
            ->orderBy('name')
            ->get();
        $this->refreshInsuranceSubTypes(true);
        $this->search ??= '';
        $this->pages = 1;
        $this->sort = $this->sort ?: 'score_desc';
        $this->minRatingCount = $this->minRatingCount ?: 1;
        $this->selectedAspect = $this->selectedAspect ?: 'allgemein';
        $this->syncSubTypeFilterState($this->selectedInsuranceSubTypefilter);
        $this->dispatchActiveEvaluationFilterAlert(immediate: true);
    }

    public function updatingSearch()
    {
        $this->resetResults();
    }

    public function updatingSort()
    {
        $this->resetResults();
    }

    public function updatingMinRatingCount()
    {
        $this->resetResults();
    }

    public function updatingMinAvgScore()
    {
        $this->resetResults();
    }

    public function updatingSelectedInsuranceTypefilter()
    {
        $this->resetResults();
    }

    public function updatedSelectedInsuranceTypefilter()
    {
        $this->selectedInsuranceTypefilter = $this->selectedInsuranceTypeIds();
        $this->refreshInsuranceSubTypes(true);
        $this->syncSubTypeFilterState($this->selectedInsuranceSubTypefilter);
        $this->dispatchActiveEvaluationFilterAlert();
    }

    public function updatingSelectedInsuranceSubTypefilter($value)
    {
        $this->resetResults();
        $this->syncSubTypeFilterState($value);
    }

    public function updatedSelectedInsuranceSubTypefilter()
    {
        $this->dispatchActiveEvaluationFilterAlert();
    }

    public function getSubTypeFilterSubTypeProperty()
    {
        return $this->subTypeFilterSubTypeId
            ? InsuranceSubtype::find($this->subTypeFilterSubTypeId)
            : null;
    }


    public function updatingInsuranceType()
    {
        $this->resetResults();
    }

    public function updatingInsuranceSubType()
    {
        $this->resetResults();
    }

    public function loadMore()
    {
        $this->pages++;
    }

    public function resetFilters()
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

        $this->syncSubTypeFilterState([]);
        $this->refreshInsuranceSubTypes();
        $this->resetResults();
        $this->dispatchActiveEvaluationFilterAlert();
    }

    public function getIsFilteredProperty()
    {
        return !empty($this->selectedInsuranceTypefilter)
            || !empty($this->selectedInsuranceSubTypefilter)
            || !empty($this->search)
            || isset($this->minAvgScore);
    }

    public function render()
    {
        $selectedInsuranceTypeIds = $this->selectedInsuranceTypeIds();
        $selectedInsuranceTypeSubtypeIds = $this->selectedInsuranceTypeSubtypeIds($selectedInsuranceTypeIds);
        $selectedInsuranceSubtypeIds = $this->selectedInsuranceSubtypeIds();

        $claimRatings = $this->insurance->publishedClaimRatings()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when(!empty($selectedInsuranceTypeIds), function ($query) use ($selectedInsuranceTypeIds, $selectedInsuranceTypeSubtypeIds) {
                $query->where(function ($typeQuery) use ($selectedInsuranceTypeIds, $selectedInsuranceTypeSubtypeIds) {
                    $typeQuery->whereIn('insurance_type_id', $selectedInsuranceTypeIds);

                    if (!empty($selectedInsuranceTypeSubtypeIds)) {
                        $typeQuery->orWhereIn('insurance_subtype_id', $selectedInsuranceTypeSubtypeIds);
                    }
                });
            })
            ->when(!empty($selectedInsuranceSubtypeIds), function ($query) use ($selectedInsuranceSubtypeIds) {
                $query->whereIn('insurance_subtype_id', $selectedInsuranceSubtypeIds);
            })
            ->when($this->sort === 'score_desc', fn ($q) => $q->orderByDesc('rating_score'))
            ->when($this->sort === 'score_asc', fn ($q) => $q->orderBy('rating_score'))
            ->when($this->sort === 'date_desc', fn ($q) => $q->orderByDesc('created_at'))
            ->when($this->sort === 'date_asc', fn ($q) => $q->orderBy('created_at'))
            ->when($this->minAvgScore, fn ($q) => $q->where('rating_score', '>=', $this->minAvgScore))
            ->paginate($this->perPage * $this->pages);


        return view('livewire.insurance.show-insurance', array_merge($this->dashboardData(), [
            'claimRatings' => $claimRatings,
            'insuranceTypes' => $this->insuranceTypes,
            'insuranceSubTypes' => $this->insuranceSubTypes,
            'isSubTypeFilter' => $this->isSubTypeFilter,
            'subTypeFilterSubType' => $this->subTypeFilterSubType,
        ]))->layout('layouts.app');
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

    private function refreshInsuranceSubTypes(bool $pruneSelection = false): void
    {
        $this->insuranceSubTypes = $this->availableInsuranceSubTypes();

        if ($pruneSelection) {
            $this->pruneSelectedInsuranceSubTypes();
        }
    }

    private function availableInsuranceSubTypes(): Collection
    {
        $selectedInsuranceTypeIds = $this->selectedInsuranceTypeIds();

        return InsuranceSubtype::query()
            ->whereHas('publishedClaimRatings', function ($q) {
                $q->where('insurance_id', $this->insurance->id);
            })
            ->when(!empty($selectedInsuranceTypeIds), function ($query) use ($selectedInsuranceTypeIds) {
                $query->whereHas('insuranceTypes', function ($typeQuery) use ($selectedInsuranceTypeIds) {
                    $typeQuery->whereIn('insurance_types.id', $selectedInsuranceTypeIds);
                });
            })
            ->orderBy('name')
            ->get();
    }

    private function pruneSelectedInsuranceSubTypes(): void
    {
        $selectedIds = $this->selectedInsuranceSubtypeIds();

        if (empty($selectedIds)) {
            return;
        }

        $availableIds = collect($this->insuranceSubTypes)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $prunedIds = array_values(array_intersect($selectedIds, $availableIds));

        if ($selectedIds !== $prunedIds) {
            $this->selectedInsuranceSubTypefilter = $prunedIds;
        }
    }

    private function normalizeFilterIds($value): array
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        $values = is_array($value) ? $value : [$value];

        return collect($values)
            ->flatMap(function ($id) {
                return is_string($id) ? explode(',', $id) : [$id];
            })
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    private function resetResults(): void
    {
        $this->resetPage();
        $this->pages = 1;
    }

    private function syncSubTypeFilterState($value): void
    {
        $selectedIds = collect($this->normalizeFilterIds($value));

        if ($selectedIds->count() === 1) {
            $this->isSubTypeFilter = true;
            $this->subTypeFilterSubTypeId = $selectedIds->first();
            $this->subTypeFilterSubType = InsuranceSubtype::find($this->subTypeFilterSubTypeId);

            return;
        }

        $this->isSubTypeFilter = false;
        $this->subTypeFilterSubTypeId = null;
        $this->subTypeFilterSubType = null;
    }

    private function dispatchActiveEvaluationFilterAlert(bool $immediate = false): void
    {
        $hasTypeFilter = !empty($this->selectedInsuranceTypeIds());
        $hasSubtypeFilter = !empty($this->selectedInsuranceSubtypeIds());

        if (!$hasTypeFilter && !$hasSubtypeFilter) {
            if (!$immediate) {
                $this->dispatch('active-evaluation-filter-alert', active: false);
            }

            return;
        }
        $message = match (true) {
            $hasTypeFilter && $hasSubtypeFilter => 'Eine Versicherungsart ist ausgewählt. Die Werte zeigen den Durchschnitt für diesen Bereich. Filter ändern für andere Versicherungsarten.',
            $hasTypeFilter => 'Eine Versicherungsart ist ausgewählt. Die Werte zeigen den Durchschnitt für diesen Bereich. Filter ändern für andere Versicherungsarten.',
            default => 'Eine Versicherungsart ist ausgewählt. Die Werte zeigen den Durchschnitt für diesen Bereich. Filter ändern für andere Versicherungsarten.',
        };

        if ($immediate) {
            $this->dispatch('showAlert', $message, 'info');

            return;
        }

        $this->dispatch(
            'active-evaluation-filter-alert',
            active: true,
            message: $message,
            type: 'info',
            title: 'Information',
        );
    }

    private function dashboardData(): array
    {
        $selectedInsuranceTypeIds = $this->selectedInsuranceTypeIds();
        $selectedInsuranceTypeSubtypeIds = $this->selectedInsuranceTypeSubtypeIds($selectedInsuranceTypeIds);
        $selectedInsuranceSubtypeIds = $this->selectedInsuranceSubtypeIds();

        $days = (int) round($this->insurance->avgRatingDurationByTypeAndSubtypeIds(
            $selectedInsuranceTypeIds,
            $selectedInsuranceTypeSubtypeIds,
            $selectedInsuranceSubtypeIds
        ));

        $detailInsuranceRating = $this->insurance->detailInsuranceRatingByTypeAndSubtypeIds(
            $selectedInsuranceTypeIds,
            $selectedInsuranceTypeSubtypeIds,
            $selectedInsuranceSubtypeIds
        );

        $scoreRaw = $detailInsuranceRating?->total_score;
        $scoreRaw ??= $this->insurance->published_ratings_avg_scoreByTypeAndSubtypeIds(
            $selectedInsuranceTypeIds,
            $selectedInsuranceTypeSubtypeIds,
            $selectedInsuranceSubtypeIds
        );
        $score5 = $scoreRaw !== null ? round($scoreRaw * 5, 1) : null;

        $regulationTypeDistribution = $this->insurance->publishedClaimRatingRegulationTypeDistributionByTypeAndSubtypeIds(
            $selectedInsuranceTypeIds,
            $selectedInsuranceTypeSubtypeIds,
            $selectedInsuranceSubtypeIds
        );
        $count = (int) ($regulationTypeDistribution['total'] ?? 0);

        $daysCap = 120;
        $daysPct = max(0, min(100, (int) round((1 - min($days, $daysCap) / $daysCap) * 100)));
        $scorePct = $score5 !== null ? (int) round(($score5 / 5) * 100) : 0;

        $teilzahlungCount = (int) ($regulationTypeDistribution['teilzahlung'] ?? 0);
        $vollzahlungCount = (int) ($regulationTypeDistribution['vollzahlung'] ?? 0);
        $ablehnungCount = (int) ($regulationTypeDistribution['ablehnung'] ?? 0);
        $austehendCount = (int) ($regulationTypeDistribution['austehend'] ?? 0);
        $otherRegulationCount = (int) ($regulationTypeDistribution['other'] ?? 0);

        $regulationTypeLegend = [
            ['label' => 'Teilzahlungen', 'count' => $teilzahlungCount, 'color' => '#f59e0b'],
            ['label' => 'Vollzahlungen', 'count' => $vollzahlungCount, 'color' => '#22c55e'],
            ['label' => 'Ablehnungen', 'count' => $ablehnungCount, 'color' => '#ef4444'],
        ];

        if ($austehendCount > 0) {
            $regulationTypeLegend[] = ['label' => 'Ausstehend', 'count' => $austehendCount, 'color' => '#3b82f6'];
        }

        if ($otherRegulationCount > 0) {
            $regulationTypeLegend[] = ['label' => 'Sonstige', 'count' => $otherRegulationCount, 'color' => '#6b7280'];
        }

        $countDonutGradient = $this->regulationTypeDonutGradient($regulationTypeLegend, $count);
        $autoSummaryItems = $this->autoSummaryItems(
            $detailInsuranceRating,
            $scoreRaw !== null ? (float) $scoreRaw : null,
            $days,
            $count,
            $teilzahlungCount,
            $vollzahlungCount,
            $ablehnungCount
        );

        return [
            'days' => $days,
            'detailInsuranceRating' => $detailInsuranceRating,
            'score5' => $score5,
            'daysPct' => $daysPct,
            'scorePct' => $scorePct,
            'count' => $count,
            'regulationTypeLegend' => $regulationTypeLegend,
            'countDonutGradient' => $countDonutGradient,
            'autoSummaryItems' => $autoSummaryItems,
            'hasDetailInsuranceRatings' => (bool) $detailInsuranceRating,
            'publishedRatingsCount' => $this->insurance->published_ratings_count(),
        ];
    }

    private function regulationTypeDonutGradient(array $regulationTypeLegend, int $count): string
    {
        if ($count <= 0) {
            return 'conic-gradient(#e5e7eb 0 100%)';
        }

        $segments = [];
        $startPct = 0.0;

        foreach ($regulationTypeLegend as $entry) {
            $entryCount = (int) ($entry['count'] ?? 0);

            if ($entryCount <= 0) {
                continue;
            }

            $endPct = round($startPct + (($entryCount / $count) * 100), 2);
            $segments[] = "{$entry['color']} {$startPct}% {$endPct}%";
            $startPct = $endPct;
        }

        $segments[] = "#e5e7eb {$startPct}% 100%";

        return 'conic-gradient(' . implode(', ', $segments) . ')';
    }

    private function autoSummaryItems(
        $detailInsuranceRating,
        ?float $scoreRaw,
        int $days,
        int $count,
        int $teilzahlungCount,
        int $vollzahlungCount,
        int $ablehnungCount
    ): array {
        $speedScore = (float) ($detailInsuranceRating->speed ?? 0);
        $communicationScore = (float) ($detailInsuranceRating->communication ?? 0);
        $fairnessScore = (float) ($detailInsuranceRating->fairness ?? 0);
        $transparencyScore = (float) ($detailInsuranceRating->transparency ?? 0);
        $communicationTransparencyAvg = ($communicationScore + $transparencyScore) / 2;
        $score5FromScoring = (float) (($scoreRaw ?? 0) * 5);
        $hasScoringData = (bool) $detailInsuranceRating;

        if ($count <= 0) {
            $summarySettlement = 'Noch zu wenig veröffentlichte Bewertungen für eine belastbare Aussage zum Regulierungsbild.';
        } else {
            $criticalSettlementCount = $teilzahlungCount + $ablehnungCount;
            $fullPaymentRatio = $vollzahlungCount / $count;

            if ($criticalSettlementCount > 0 && ($criticalSettlementCount / $count) >= 0.45) {
                $summarySettlement = "{$criticalSettlementCount} von {$count} Fällen enden mit Teilzahlung oder Ablehnung.";
            } elseif ($vollzahlungCount > 0 && $fullPaymentRatio >= 0.6) {
                $summarySettlement = "{$vollzahlungCount} von {$count} Fällen wurden als Vollzahlung reguliert.";
            } elseif ($vollzahlungCount > $criticalSettlementCount && $fullPaymentRatio >= 0.4) {
                $summarySettlement = "Vollzahlungen überwiegen derzeit mit {$vollzahlungCount} von {$count} Fällen.";
            } elseif ($ablehnungCount > 0 && ($ablehnungCount / $count) >= 0.2) {
                $summarySettlement = "Ablehnungen sind mit {$ablehnungCount} von {$count} Fällen überdurchschnittlich präsent.";
            } else {
                $summarySettlement = 'Regulierungsarten zeigen derzeit ein gemischtes Bild ohne starke Ausreißer.';
            }
        }

        if ($days >= 45 || ($hasScoringData && $speedScore <= 0.45)) {
            $summarySpeed = "Häufige Verzögerungen bei der Schadensbearbeitung (Durchschnitt {$days} Tage).";
        } elseif ($days > 0 && $days <= 21 && (!$hasScoringData || $speedScore >= 0.65)) {
            $summarySpeed = "Bearbeitung wirkt insgesamt zügig (Durchschnitt {$days} Tage).";
        } else {
            $summarySpeed = 'Bearbeitungsgeschwindigkeit liegt aktuell im mittleren Bereich.';
        }

        if (!$hasScoringData) {
            $summaryService = 'Kommunikation und Transparenz werden nach weiteren Scoring-Daten genauer eingeordnet.';
        } elseif ($communicationTransparencyAvg <= 0.5) {
            $summaryService = 'Kommunikation und Transparenz werden überwiegend nur mittelmäßig bewertet.';
        } elseif ($communicationTransparencyAvg >= 0.75) {
            $summaryService = 'Kommunikation und Transparenz werden überwiegend positiv hervorgehoben.';
        } else {
            $summaryService = 'Kommunikation und Transparenz wirken insgesamt ausgeglichen.';
        }

        if (!$hasScoringData) {
            $summaryFairness = 'Fairness und Gesamtwirkung werden mit weiteren Scoring-Daten präziser eingeordnet.';
        } elseif ($fairnessScore <= 0.5 || $score5FromScoring < 2.8) {
            $summaryFairness = 'Entscheidungen werden bei Fairness und Gesamtwirkung häufig kritisch eingeschätzt.';
        } elseif ($fairnessScore >= 0.75 && $score5FromScoring >= 4.0) {
            $summaryFairness = 'Fairness und Gesamtwirkung werden überwiegend positiv bewertet.';
        } else {
            $summaryFairness = 'Fairness und Gesamtwirkung liegen aktuell im soliden Mittelfeld.';
        }

        return [
            [
                'icon' => 'fa-file-invoice-dollar',
                'icon_bg' => 'bg-teal-600',
                'title' => 'Regulierungsbild',
                'text' => $summarySettlement,
            ],
            [
                'icon' => 'fa-clock',
                'icon_bg' => 'bg-teal-600',
                'title' => 'Bearbeitungsdauer',
                'text' => $summarySpeed,
            ],
            [
                'icon' => 'fa-comments',
                'icon_bg' => 'bg-teal-600',
                'title' => 'Kommunikation',
                'text' => $summaryService,
            ],
            [
                'icon' => 'fa-balance-scale',
                'icon_bg' => 'bg-teal-600',
                'title' => 'Fairness',
                'text' => $summaryFairness,
            ],
        ];
    }
}

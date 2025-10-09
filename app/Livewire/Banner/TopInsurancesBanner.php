<?php

namespace App\Livewire\Banner;

use Livewire\Component;
use App\Models\Insurance;

class TopInsurancesBanner extends Component
{
    public $insurances;

    // Optional-Filter (falls du später Subtypen nutzen willst)
    public bool $isSubTypeFilter = false;
    public ?int $subTypeFilterSubType = null;

    public int $limit = 20;
    public int $minPublishedCount = 1; // ggf. auf 3+ erhöhen

    public function mount()
    {
        $applySubtype = $this->isSubTypeFilter && $this->subTypeFilterSubType;

        $this->insurances = Insurance::query()
            ->where('is_active', true)
            // Anzahl veröffentlichter Ratings
            ->withCount([
                'publishedClaimRatings as published_count' => function ($q) use ($applySubtype) {
                    if ($applySubtype) {
                        $q->where('insurance_subtype_id', $this->subTypeFilterSubType);
                    }
                },
            ])
            // Ø-Score nur aus veröffentlichten Ratings
            ->withAvg([
                'publishedClaimRatings as published_avg' => function ($q) use ($applySubtype) {
                    if ($applySubtype) {
                        $q->where('insurance_subtype_id', $this->subTypeFilterSubType);
                    }
                },
            ], 'rating_score')
            ->having('published_count', '>=', $this->minPublishedCount)
            ->orderByDesc('published_count')
            ->take($this->limit)
            ->get();
    }

    public function render()
    {
        return view('livewire.banner.top-insurances-banner');
    }
}

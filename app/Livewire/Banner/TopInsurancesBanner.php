<?php

namespace App\Livewire\Banner;

use Livewire\Component;
use App\Models\Insurance;

class TopInsurancesBanner extends Component
{
    public $insurances;
    public $isSubTypeFilter = false;
    public $subTypeFilterSubType;

    public function mount()
    {
        // Top 5 Versicherungen nach Bewertungsdurchschnitt
        $this->insurances = Insurance::withAvg('claimRatings', 'rating_score')
            ->orderByDesc('claim_ratings_avg_rating_score')
            ->take(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.banner.top-insurances-banner');
    }
}

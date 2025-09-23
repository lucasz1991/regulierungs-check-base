<?php

namespace App\Livewire\Banner;

use Livewire\Component;
use App\Models\Insurance;

class TopInsurancesBanner extends Component
{
    public $insurances;

    public function mount()
    {
        // Top 5 Versicherungen nach Bewertungsdurchschnitt
        $this->insurances = Insurance::with('style')
            ->withAvg('claimRatings', 'score')
            ->orderByDesc('claim_ratings_avg_score')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.banner.top-insurances-banner');
    }
}

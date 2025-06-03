<?php

namespace App\Livewire\Pages;

use App\Models\Insurance;
use Livewire\Component;

class Ranking extends Component
{
    public $allInsurances;
    public $top5;
    public $flop5;
    public $perPage = 20;
    public $pages = 1;
    public $sort = 'score_desc';

    public $subtypeFilter = [];

    public $aspectFilter;

    protected $listeners = ['refreshRanking' => '$refresh'];

    public function mount()
    {
        $this->top5 = Insurance::withAvg('claimRatings as avg_score', 'rating_score')
            ->whereHas('claimRatings', function ($query) {
                $query->whereNotNull('rating_score');
            })
            ->orderByDesc('avg_score')
            ->take(5)
            ->get();

        $this->flop5 = Insurance::withAvg('claimRatings as avg_score', 'rating_score')
            ->whereHas('claimRatings', function ($query) {
                $query->whereNotNull('rating_score');
            })
            ->orderBy('avg_score')
            ->take(5)
            ->get();

        $this->allInsurances = Insurance::withAvg('claimRatings as avg_score', 'rating_score')
            ->whereHas('claimRatings', function ($query) {
                $query->whereNotNull('rating_score');
            })
            ->orderByDesc('avg_score')
            ->get();
    }
    public function render()
    {

        return view('livewire.pages.ranking')->layout('layouts.app');
    }
}

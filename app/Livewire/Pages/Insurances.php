<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Insurance;
use App\Models\InsuranceType;
use App\Models\InsuranceSubtype;

class Insurances extends Component
{
    use WithPagination;

    public $search;
    public $insuranceTypes = [];
    public $selectedInsuranceTypesfilter = [];
    public $insuranceSubTypes = [];
    public $selectedInsuranceSubTypefilter = [];
    public $perPage = 20;
    public $pages = 1;


    public $sort;
    public $minRatingCount;
    public $minAvgScore;
    public $onlyActive;




    public function mount()
    {
        $this->insuranceSubTypes = InsuranceSubtype::whereHas('claimRatings')->get();
        $this->search = '';
        $this->pages = 1;
        $this->sort = 'score_desc';
        $this->minRatingCount = 1;
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSort()
    {
        $this->resetPage();
    }
    public function updatingMinRatingCount()
    {
        $this->resetPage();
    }
    public function updatingMinAvgScore()
    {
        $this->resetPage();
    }
    public function updatingOnlyActive()
    {
        $this->resetPage();
    }
    public function updatingSelectedInsuranceSubTypefilter()
    {
        $this->resetPage();
    }

    public function updatingInsuranceType()
    {
        $this->resetPage();
    }

    public function updatingInsuranceSubType()
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->pages++;
    }

public function resetFilters()
{
    $this->reset([
        'selectedInsuranceSubTypefilter',
        'search',
        'minRatingCount',
        'minAvgScore',
        'onlyActive',
    ]);
}


    public function render()
    {
        $insurances = Insurance::query()
            ->withCount('claimRatings')
            ->withAvg('claimRatings', 'rating_score')
            ->whereHas('claimRatings', function ($query) {
                $query->where('status', 'rated');
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when(!empty($this->selectedInsuranceSubTypefilter), function ($query) {
    $query->whereHas('claimRatings', function ($subQuery) {
        $subQuery->where('status', 'rated')
                 ->whereIn('insurance_subtype_id', $this->selectedInsuranceSubTypefilter);
    });
})

            ->when($this->sort === 'score_desc', fn ($q) => $q->orderByDesc('claim_ratings_avg_rating_score'))
            ->when($this->sort === 'score_asc', fn ($q) => $q->orderBy('claim_ratings_avg_rating_score'))
            ->when($this->sort === 'count_desc', fn ($q) => $q->orderByDesc('claim_ratings_count'))
            ->when($this->sort === 'count_asc', fn ($q) => $q->orderBy('claim_ratings_count'))
            ->when($this->minRatingCount, fn ($q) => $q->having('claim_ratings_count', '>=', $this->minRatingCount))
            ->when($this->minAvgScore, fn ($q) => $q->having('claim_ratings_avg_rating_score', '>=', $this->minAvgScore))
            ->paginate($this->perPage*$this->pages);
    

    
        return view('livewire.pages.insurances', [
            'insurances' => $insurances,
            'insuranceTypes' => $this->insuranceTypes,
            'insuranceSubTypes' => $this->insuranceSubTypes,
        ])->layout('layouts.app');
    }
    
}

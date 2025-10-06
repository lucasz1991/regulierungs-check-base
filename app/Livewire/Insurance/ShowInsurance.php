<?php

namespace App\Livewire\Insurance;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;
use App\Models\Insurance;
use App\Models\ClaimRating;
use App\Models\InsuranceType;
use App\Models\InsuranceSubtype;

class ShowInsurance extends Component
{
    use WithPagination;

    public Insurance $insurance;

    public $search;
    public $insuranceSubTypes = [];
    #[Session(key: 'selectedInsuranceSubTypefilter')] 
    public $selectedInsuranceSubTypefilter = [];
    public $perPage = 20;
    public $pages = 1;


    public $selectedAspect;
    public $sort;
    public $minRatingCount;
    public $minAvgScore;
    public $onlyActive;

    public $isSubTypeFilter;
    public $subTypeFilterSubType;


    public function mount(Insurance $insurance)
    {
        $this->insurance = $insurance;
        $this->insuranceSubTypes = InsuranceSubtype::whereHas('claimRatings')->get();
        $this->search = '';
        $this->pages = 1;
        $this->sort = 'score_desc';
        $this->minRatingCount = 1;
        $this->selectedAspect = 'allgemein';
        $this->isSubTypeFilter = false;
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



    public function updatingSelectedInsuranceSubTypefilter($value)
    {
        $this->resetPage();

        if (is_array($value) && count($value) === 1) {
            $this->isSubTypeFilter = true;
            $this->subTypeFilterSubType = InsuranceSubtype::find($value[0]);
        } else {
            $this->isSubTypeFilter = false;
            $this->subTypeFilterSubType = null;
        }
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

    public function getIsFilteredProperty()
    {
        return !empty($this->selectedInsuranceSubTypefilter) || !empty($this->minRatingCount) || !empty($this->search) || isset($this->minAvgScore);
    }

    public function render()
    {
        $claimRatings = $this->insurance->claimRatings()
            ->where('status', 'rated')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when(!empty($this->selectedInsuranceSubTypefilter), function ($query) {
                $query->whereIn('insurance_subtype_id', $this->selectedInsuranceSubTypefilter);
            })
            ->when($this->sort === 'score_desc', fn ($q) => $q->orderByDesc('rating_score'))
            ->when($this->sort === 'score_asc', fn ($q) => $q->orderBy('rating_score'))
            ->when($this->sort === 'date_desc', fn ($q) => $q->orderByDesc('created_at'))
            ->when($this->sort === 'date_asc', fn ($q) => $q->orderBy('created_at'))
            ->when($this->minAvgScore, fn ($q) => $q->where('rating_score', '>=', $this->minAvgScore))
            ->paginate($this->perPage * $this->pages);


        return view('livewire.insurance.show-insurance', [
            'claimRatings' => $claimRatings,
            'insuranceSubTypes' => $this->insuranceSubTypes,
            'isSubTypeFilter' => $this->isSubTypeFilter,
            'subTypeFilterSubType' => $this->subTypeFilterSubType,
        ])->layout('layouts.app');
    }
}

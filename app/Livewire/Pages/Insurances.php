<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Insurance;
use App\Models\InsuranceType;
use App\Models\InsuranceSubType;

class Insurances extends Component
{
    use WithPagination;

    public $search = '';
    public $insuranceTypes = [];
    public $insuranceType = null;
    public $insuranceSubTypes = [];
    public $insuranceSubType = null;
    public $perPage = 9;
    public $pages = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingSubType()
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->pages++;
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
            ->when($this->insuranceType, function ($query) {
                $query->whereHas('insuranceTypes', function ($query) {
                    // Klarer Verweis auf die Tabelle insurance_types
                    $query->where('insurance_types.id', (int) $this->insuranceType);
                });
            })
            ->when($this->insuranceSubType, function ($query) {
                $query->whereHas('insuranceTypes.insuranceSubTypes', function ($subQuery) {
                    $subQuery->where('insurance_subtypes.id', (int) $this->insuranceSubType);
                });
            })
            ->paginate($this->perPage*$this->pages);
    
            $this->insuranceTypes = InsuranceType::all();
            $this->insuranceSubTypes = InsuranceSubType::all();
    
        return view('livewire.pages.insurances', [
            'insurances' => $insurances,
            'insuranceTypes' => $this->insuranceTypes,
            'insuranceSubTypes' => $this->insuranceSubTypes,
        ])->layout('layouts.app');
    }
    
}

<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Insurance;
use App\Models\InsuranceType;

class Insurances extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';

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

    public function render()
    {
        $insurances = Insurance::query()
            ->withCount('claimRatings')
            ->withAvg('claimRatings', 'rating_score')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->type, function ($query) {
                $query->whereHas('insuranceTypes', function ($query) {
                    // Klarer Verweis auf die Tabelle insurance_types
                    $query->where('insurance_types.id', (int) $this->type);
                });
            })
            ->paginate(9);
    
        $insuranceTypes = InsuranceType::all();
    
        return view('livewire.pages.insurances', [
            'insurances' => $insurances,
            'insuranceTypes' => $insuranceTypes,
        ])->layout('layouts.app');
    }
    
}

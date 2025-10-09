<?php

namespace App\Livewire\Insurance;

use Livewire\Component;
use App\Models\InsuranceSubtype;

class ShowSubtype extends Component
{
    public InsuranceSubtype $insuranceSubtype;
    public $claimRatings;

    public function mount(InsuranceSubtype $insuranceSubtype)
    {
        $this->insuranceSubtype = $insuranceSubtype;
        $this->claimRatings = $insuranceSubtype->publishedClaimRatings()
        ->latest()         // sortiert nach created_at DESC
        ->take(10)          // nur 3 Bewertungen
        ->get();
    } 

    public function render()
    {
        return view('livewire.insurance.show-subtype')->layout('layouts.app');
    }
}

<?php

namespace App\Livewire\Insurance;

use Livewire\Component;
use App\Models\InsuranceSubtype;

class ShowSubtype extends Component
{
    public InsuranceSubtype $insuranceSubtype;

    public function mount(InsuranceSubtype $insuranceSubtype)
    {
        $this->insuranceSubtype = $insuranceSubtype;
    } 

    public function render()
    {
        return view('livewire.insurance.show-subtype')->layout('layouts.app');
    }
}

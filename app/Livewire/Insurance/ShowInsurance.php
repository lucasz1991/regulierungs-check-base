<?php

namespace App\Livewire\Insurance;

use Livewire\Component;
use App\Models\Insurance;

class ShowInsurance extends Component
{
    public Insurance $insurance;

    public function mount(Insurance $insurance)
    {
        $this->insurance = $insurance;
    }

    public function render()
    {
        return view('livewire.insurance.show-insurance')->layout('layouts.app');
    }
}

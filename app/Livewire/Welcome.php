<?php

namespace App\Livewire;

use App\Models\ClaimRating;
use Livewire\Component;

class Welcome extends Component
{
    public int $ratedInsurerCount = 0;

    public function mount()
    {
        // Echte Anzahl an Versicherern, die mindestens eine öffentliche Bewertung haben
        $this->ratedInsurerCount = (int) ClaimRating::publiclyVisible()
            ->whereNotNull('insurance_id')
            ->distinct('insurance_id')
            ->count('insurance_id');
    }

    public function render()
    {
        return view('livewire.welcome')->layout('layouts/app');
    }
}

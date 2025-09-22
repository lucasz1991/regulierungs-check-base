<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class RewardQuest extends Component
{
    public $refferer;
    public $showTermsModal = false;

    public function mount()
    {
        $this->refferer = url()->previous();
    }

    public function render()
    {
        return view('livewire.pages.reward-quest')->layout('layouts.app');
    }
}

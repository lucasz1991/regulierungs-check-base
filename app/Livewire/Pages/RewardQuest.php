<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\On;

class RewardQuest extends Component
{
    public $refferer;
    public $showTermsModal = false;

    public function mount()
    {
        $this->refferer = url()->previous();
    }

    #[On('showTermsModal')]
    public function showTermsModal()
    {
        $this->showTermsModal = true;
    }

    public function render()
    {
        return view('livewire.pages.reward-quest')->layout('layouts.app');
    }
}

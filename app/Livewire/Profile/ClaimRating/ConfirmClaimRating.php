<?php

namespace App\Livewire\Profile\ClaimRating;

use Livewire\Component;
use App\Models\ClaimRating;
use Illuminate\Support\Facades\Auth;

class ConfirmClaimRating extends Component
{
    public $claimRating;
    public $openModal = false;

    protected $listeners = ['claimRatingConfirm'];

    public function mount()
    {
        $this->claimRating = null;
    }

    public function claimRatingConfirm($claimRatingId)
    {
        $this->claimRating = ClaimRating::find($claimRatingId);

        $this->openModal = true;
    }

    public function confirm()
    {
        if (!$this->claimRating) return;

        $this->claimRating->update([
            'is_public' => true,
        ]);

        $this->dispatch('refreshParent'); // damit ClaimRatingList neu lädt
        $this->openModal = false;
        session()->flash('message', 'Bewertung wurde veröffentlicht.');
    }

    public function render()
    {
        return view('livewire.profile.claim-rating.confirm-claim-rating');
    }
}
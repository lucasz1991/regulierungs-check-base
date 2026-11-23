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
        // The event can be dispatched from the browser with any ID, so only the owner's own rating may be loaded.
        $this->claimRating = $this->ownedClaimRating($claimRatingId);

        $this->openModal = $this->claimRating !== null;
    }

    public function confirm()
    {
        $claimRating = $this->ownedClaimRating($this->claimRating?->id);

        if (! $claimRating) {
            $this->closeWithoutRating();
            return;
        }

        if (Auth::user()->email_verified_at === null || ! $claimRating->canBePublished()) {
            $this->openModal = false;
            session()->flash('message', 'Diese Bewertung kann aktuell nicht veröffentlicht werden.');
            return;
        }

        $claimRating->update([
            'is_public' => true,
        ]);

        $this->claimRating = $claimRating;
        $this->dispatch('refreshParent'); // damit ClaimRatingList neu lädt
        $this->openModal = false;
        session()->flash('message', 'Bewertung wurde veröffentlicht.');
    }

    public function reanalyze()
    {
        $claimRating = $this->ownedClaimRating($this->claimRating?->id);

        abort_if($claimRating === null, 403);

        $claimRating->reanalyze();
        $this->claimRating = $claimRating;
    }

    public function render()
    {
        return view('livewire.profile.claim-rating.confirm-claim-rating');
    }

    private function ownedClaimRating($claimRatingId): ?ClaimRating
    {
        if (! Auth::check() || $claimRatingId === null || ! is_numeric($claimRatingId)) {
            return null;
        }

        return ClaimRating::whereKey((int) $claimRatingId)
            ->where('user_id', Auth::id())
            ->first();
    }

    private function closeWithoutRating(): void
    {
        $this->claimRating = null;
        $this->openModal = false;
    }
}

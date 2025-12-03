<?php

namespace App\Livewire\Profile\ClaimRating;

use Livewire\Component;
use App\Models\ClaimRating;
use Illuminate\Support\Facades\Auth;

class ShowClaimRating extends Component
{
    public ClaimRating $claimRating;
    public $hasActiveRating;

    public function mount(ClaimRating $claimRating)
    {
        abort_if($claimRating->user_id !== Auth::id(), 403);
        $this->claimRating = $claimRating;
    }

    public function reanalyze()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);

        $this->claimRating->reanalyze();

        session()->flash('message', 'Die Bewertung wurde zur erneuten Analyse eingereicht.');
    }

    public function unpublish()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);

        $this->claimRating->is_public = false;
        $this->claimRating->status    = ClaimRating::STATUS_APPROVED; // oder was dein Flow hier vorsieht
        $this->claimRating->save();

        session()->flash('message', 'Die Bewertung wurde auf privat gesetzt.');
    }

    public function publish()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);

        // HIER: zentrale Prüfung über das Model
        if (! $this->claimRating->canBePublished()) {

            // Optional: etwas differenziertere Meldung
            if ($this->claimRating->requiresVerification()) {
                session()->flash(
                    'message',
                    'Diese Bewertung kann noch nicht veröffentlicht werden. Bitte hinterlege zuerst Fallnummer und Falldokumente und warte auf die Verifikation.'
                );
            } else {
                session()->flash(
                    'message',
                    'Diese Bewertung kann aktuell nicht veröffentlicht werden.'
                );
            }

            return;
        }

        // Alles erfüllt → freigeben
        $this->claimRating->is_public = true;
        $this->claimRating->status    = ClaimRating::STATUS_PUBLISHED;
        $this->claimRating->save();

        session()->flash('message', 'Bewertung veröffentlicht.');
    }

    public function delete()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);

        $this->claimRating->delete();

        return redirect()
            ->route('dashboard')
            ->with('message', 'Claim rating deleted successfully.');
    }

    public function render()
    {
        $this->hasActiveRating = $this->claimRating->status === ClaimRating::STATUS_PENDING;

        return view('livewire.profile.claim-rating.show-claim-rating')
            ->layout('layouts.app');
    }
}

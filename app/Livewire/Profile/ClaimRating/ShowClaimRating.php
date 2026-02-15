<?php

namespace App\Livewire\Profile\ClaimRating;

use Livewire\Component;
use App\Models\ClaimRating;
use Illuminate\Support\Facades\Auth;

class ShowClaimRating extends Component
{
    public ClaimRating $claimRating;

    public bool $hasActiveRating = false;

    /** Verifikationsdaten aus data['verification'] */
    public array $verification = [];

    /** Ob diese Bewertung eine Verifikation benötigt (Mehrfachbewertung) */
    public bool $requiresVerification = false;

    /** Ob diese Bewertung aktuell veröffentlicht werden darf */
    public bool $canBePublished = false;
    public bool $showVerificationSection = false;

    public function mount(ClaimRating $claimRating)
    {
        abort_if($claimRating->user_id !== Auth::id(), 403);

        $this->claimRating = $claimRating;

        $this->refreshVerificationState();
    }

    protected function refreshVerificationState(): void
    {
        // falls das Model zwischendurch verändert/neu geladen wurde
        $this->claimRating->refresh();

        $this->verification          = $this->claimRating->verification;
        $this->requiresVerification  = $this->claimRating->requiresVerification();
        $this->canBePublished        = $this->claimRating->canBePublished();
        $this->showVerificationSection = $this->requiresVerification || $this->claimRating->hasVerification();
        $this->hasActiveRating       = $this->claimRating->status === ClaimRating::STATUS_PENDING;
    }

    public function reanalyze()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);

        $this->claimRating->reanalyze();

        $this->refreshVerificationState();

        session()->flash('message', 'Die Bewertung wurde zur erneuten Analyse eingereicht.');
    }

    public function unpublish()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);

        $this->claimRating->is_public = false;
        $this->claimRating->status    = ClaimRating::STATUS_APPROVED; // oder dein gewünschter Zwischenstatus
        $this->claimRating->save();

        $this->refreshVerificationState();

        session()->flash('message', 'Die Bewertung wurde auf privat gesetzt.');
    }

    public function publish()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);

        if (! $this->claimRating->canBePublished()) {

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

            $this->refreshVerificationState();

            return;
        }

        $this->claimRating->is_public = true;
        $this->claimRating->status    = ClaimRating::STATUS_PUBLISHED;
        $this->claimRating->save();

        $this->refreshVerificationState();

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
        // falls sich zwischendurch etwas am Model geändert hat (Queue etc.)
        $this->refreshVerificationState();

        return view('livewire.profile.claim-rating.show-claim-rating')
            ->layout('layouts.app');
    }
}

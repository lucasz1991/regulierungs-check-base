<?php

namespace App\Livewire\ClaimRatings;

use Livewire\Component;
use App\Models\ClaimRating;
use App\Models\ClaimRatingReport;
use Illuminate\Support\Facades\Auth;

class ReportClaimRatingForm extends Component
{
    public $showFormModal = false;
    public $claimRating;
    public $reportReason = '';
    public $comment = '';
    public $confirmed = false;

    protected $listeners = [
        'showReportClaimForm' => 'showFormModal',
    ];

    public function mount($claimRatingId)
    {
        $this->claimRating = ClaimRating::find($claimRatingId);

        if (!$this->claimRating) {
            session()->flash('error', 'Die Bewertung wurde nicht gefunden.');
            return redirect()->route('claim-ratings.index');
        }
    }

    public function showFormModal()
    {
        $this->showFormModal = true;
    }

    public function rules()
    {
        return [
            'reportReason' => 'required|in:beleidigung,spam,falschinformation,datenschutz,sonstiges',
            'comment' => 'required|string|min:10|max:1000',
            'confirmed' => 'required|accepted',
        ];
    }

    public function messages()
    {
        return [
            'reportReason.required' => 'Bitte wähle einen Grund für die Meldung aus.',
            'comment.required' => 'Bitte gib einen Kommentar ein.',
            'comment.min' => 'Der Kommentar muss mindestens 10 Zeichen lang sein.',
            'comment.max' => 'Der Kommentar darf maximal 1000 Zeichen lang sein.',
            'confirmed.accepted' => 'Bitte bestätige, dass du die Meldung absenden möchtest.',
        ];
    }

    public function submit()
    {
        $this->validate();

        ClaimRatingReport::create([
            'claim_rating_id' => $this->claimRating->id,
            'user_id' => Auth::id(),
            'reason' => $this->reportReason,
            'comment' => $this->comment,
            'status' => 'pending',
        ]);

        $this->dispatch('showAlert', 'Vielen Dank! Wir haben deine Meldung erhalten.');
        $this->reset(['reportReason', 'comment', 'confirmed', 'showFormModal']);
    }

    public function render()
    {
        return view('livewire.claim-ratings.report-claim-rating-form');
    }
}

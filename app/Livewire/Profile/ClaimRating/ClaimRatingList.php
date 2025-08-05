<?php

namespace App\Livewire\Profile\ClaimRating;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\ClaimRating;

class ClaimRatingList extends Component
{
    use WithPagination;

    public $userData;
    public $hasActiveRating;
    public $privateClaimRating;

    protected $listeners = [
        'refreshParent' => '$refresh',
        'deleteRating' => 'deleteRating'
    ];

    public function mount()
    {
        $this->userData = Auth::user();
        $this->hasActiveRating = false;
        $this->privateClaimRating = ClaimRating::where('user_id', $this->userData->id)
            ->where('is_public', false)
            ->where('status', '=', 'rated')
            ->latest()
            ->first();
    }

    public function deleteRating($ratingId)
    {
        $rating = ClaimRating::find($ratingId);
        if ($rating && $rating->user_id === Auth::id()) {
            $rating->delete();
            session()->flash('message', 'Rating deleted successfully.');
        } else {
            session()->flash('error', 'You are not authorized to delete this rating.');
        }
    }

    public function render()
    {
        $this->userData = Auth::user();

        $claimRatings = ClaimRating::where('user_id', $this->userData->id)
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        $this->hasActiveRating = $claimRatings->contains('status', 'pending');
        if ($this->privateClaimRating && !$this->privateClaimRating->is_public) {
            $this->dispatch('claimRatingConfirm', $this->privateClaimRating->id);
            $this->privateClaimRating = null; // wichtig: nur einmal senden
        }
        return view('livewire.profile.claim-rating.claim-rating-list', [
            'claimRatings' => $claimRatings,
        ]);
    }
}

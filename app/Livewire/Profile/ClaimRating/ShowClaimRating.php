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
        session()->flash('message', 'Claim rating unpublished successfully.');
    }

    public function unpublish()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);
        $this->claimRating->is_public = false;
        $this->claimRating->save();
        session()->flash('message', 'Claim rating unpublished successfully.');
    }

    public function publish()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);
        $this->claimRating->is_public = true;
        $this->claimRating->save();
        session()->flash('message', 'Claim rating published successfully.');
    }

    public function delete()
    {
        abort_if($this->claimRating->user_id !== Auth::id(), 403);
        $this->claimRating->delete();
        return redirect()->route('dashboard')->with('message', 'Claim rating deleted successfully.');
    }

    public function render()
    {
        $this->hasActiveRating = $this->claimRating->status === 'pending';
        return view('livewire.profile.claim-rating.show-claim-rating')->layout("layouts.app");
    }
}

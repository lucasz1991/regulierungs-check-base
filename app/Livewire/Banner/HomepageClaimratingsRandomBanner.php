<?php

namespace App\Livewire\Banner;

use Livewire\Component;
use App\Models\ClaimRating;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HomepageClaimratingsRandomBanner extends Component
{
    private const LIMIT = 15;

    public Collection $claimRatings;

    public function mount()
    {
        $preferredClaimRatings = $this->claimRatingQuery()
            ->whereHas('user', fn (Builder $query) => $query
                ->where('privacy_settings->ratings->name_visibility', 'all')
                ->where('privacy_settings->ratings->avatar_visibility', 'all')
            )
            ->inRandomOrder()
            ->take(self::LIMIT)
            ->get();

        $fallbackClaimRatings = collect();

        if ($preferredClaimRatings->count() < self::LIMIT) {
            $fallbackClaimRatings = $this->claimRatingQuery()
                ->whereNotIn('id', $preferredClaimRatings->pluck('id'))
                ->inRandomOrder()
                ->take(self::LIMIT - $preferredClaimRatings->count())
                ->get();
        }

        $this->claimRatings = $preferredClaimRatings
            ->concat($fallbackClaimRatings)
            ->shuffle()
            ->values();
    }

    private function claimRatingQuery(): Builder
    {
        return ClaimRating::with(['insurance', 'insuranceSubtype', 'user'])
            ->publiclyVisible();
    }

    public function render()
    {
        return view('livewire.banner.homepage-claimratings-random-banner');
    }
}

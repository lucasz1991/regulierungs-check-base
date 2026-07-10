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
        // 50/50-Mix: Hälfte Bewertungen mit sichtbarem Namen, Hälfte anonym
        $namedTarget = (int) ceil(self::LIMIT / 2);
        $anonymousTarget = self::LIMIT - $namedTarget;

        $namedClaimRatings = $this->claimRatingQuery()
            ->whereHas('user', fn (Builder $query) => $query
                ->where('privacy_settings->ratings->name_visibility', 'all')
            )
            ->inRandomOrder()
            ->take($namedTarget)
            ->get();

        $anonymousClaimRatings = $this->claimRatingQuery()
            ->whereDoesntHave('user', fn (Builder $query) => $query
                ->where('privacy_settings->ratings->name_visibility', 'all')
            )
            ->inRandomOrder()
            ->take($anonymousTarget)
            ->get();

        // Fehlende Plätze aus dem jeweils anderen Pool auffüllen
        $missing = self::LIMIT - $namedClaimRatings->count() - $anonymousClaimRatings->count();

        $fillClaimRatings = collect();
        if ($missing > 0) {
            $fillClaimRatings = $this->claimRatingQuery()
                ->whereNotIn('id', $namedClaimRatings->pluck('id')->merge($anonymousClaimRatings->pluck('id')))
                ->inRandomOrder()
                ->take($missing)
                ->get();
        }

        $this->claimRatings = $namedClaimRatings
            ->concat($anonymousClaimRatings)
            ->concat($fillClaimRatings)
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

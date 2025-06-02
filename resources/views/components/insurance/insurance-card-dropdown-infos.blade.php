<div class=" items-center justify-between">
    <div>
        @if($insurance->claim_ratings_count > 0)
            <x-insurance.insurance-rating-stars :score="$insurance->claim_ratings_avg_rating_score" />
        @else
            <span class="text-gray-500">Keine Bewertungen</span>
        @endif
    </div>
    <div>
        <span class="font-sm text-gray-700 p-1 px-2 bg-slate-100 rounded-lg">Ø Dauer: {{ $insurance->avgRatingDuration() ?? 29 }} Tage</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-gray-500 text-sm">Bewertungen: {{ $insurance->claim_ratings_count }}</span>
        <a href="{{ route('insurance.show-insurance', $insurance->slug) }}" class="text-blue-600 hover:underline text-sm">Mehr Infos</a>
    </div>    
</div>
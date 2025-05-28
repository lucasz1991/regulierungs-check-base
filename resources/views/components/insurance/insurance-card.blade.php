<a href="/insurance/{{ $insurance->slug }}" wire:navigate class="block">
    <div class="bg-white rounded border border-gray-200 shadow  transition-shadow duration-300 p-4 flex flex-col justify-between h-full  hover:shadow-lg ">
        <div class="flex gap-4 mb-4">
            <div class="shrink-0 flex-none ">
                <div class="font-semibold w-min rounded flex items-center justify-center border text-base  px-2" style="background-color: {{ $insurance->style['bg_color'] ?? '#ccc' }}; color: {{ $insurance->style['font_color'] ?? '#000' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                    {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
                </div>
            </div>
            <div class="grow max-w-full">
                <h2 class="text-xl break-words  mb-2 w-full truncate ">
                    {{ strlen($insurance->name) > 25 ? substr($insurance->name, 0, 25) . '...' : $insurance->name }}
                </h2>
            </div>
        </div>
        <div class="flex items-center justify-between">
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
        </div>
    </div>
</a>

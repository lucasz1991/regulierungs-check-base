<a href="/insurance/{{ $insurance->slug }}" wire:navigate class="block">
    <div class="bg-white rounded border border-gray-200 shadow  transition-shadow duration-300 p-4 flex flex-col justify-between h-full  hover:shadow-lg ">
            <div class="opacity-70 hover:opacity-100  transition-all duration-200 cursor-pointer">
                <div  class="flex gap-2 overflow-hidden">
                    <div class="flex-none shrink-0">
                        <div class=" w-min rounded flex items-center justify-center text-base border px-2 font-medium" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                            {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
                        </div>
                    </div>
                    <div class="grow">
                        <div >
                        <h2 class="text-base break-words  truncate text-ellipsis">
                            {{  $insurance->name }}
                        </h2>
                        </div>
                    </div>
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

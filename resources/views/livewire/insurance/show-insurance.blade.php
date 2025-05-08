<div>
    <div class="container mx-auto px-4 pt-12 py-6">
        <div class="">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold" style="background-color: {{ $insurance->color ?? '#ccc' }};">
                    {{ strtoupper(substr($insurance->initials, 0, 4)) }}
                </div>
                <h1 class="text-2xl font-bold ml-4">{{ $insurance->name }}</h1>
            </div>
            <p class="text-gray-600 mb-4">{{ $insurance->description }}</p>
            @if($insurance->ratings_count() > 0)
             <x-insurance.insurance-rating-stars :score="$insurance->ratings_avg_score()" />
            @else
                <span class="text-gray-500">Keine Bewertungen</span>
            @endif
        </div>
        <div class="mt-12">
            @if($insurance->ratings_count() > 0)
                <h2 class="flex items-center justify-center text-lg px-2 py-1 w-max mb-5">
                    <span class="w-max">Bewertungen</span>
                    <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                        {{ $insurance->ratings_count() }}
                    </span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($insurance->claimRatings as $claim_rating)
                        <div class="bg-white rounded-lg border border-gray-200 shadow p-4">
                            <div class=" flex items-center content-center justify-between">
                                <x-insurance.insurance-rating-stars :score="$claim_rating->rating_score" />
                                <p class="text-gray-600 ">
                                    {{ $claim_rating->created_at ? $claim_rating->created_at->diffForHumans() : 'Datum nicht verfügbar' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

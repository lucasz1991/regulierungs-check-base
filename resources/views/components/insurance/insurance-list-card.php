@props(['insurance'])

<a href="/insurance/{{ $insurance->id }}" wire:navigate class="block">
    <div class="bg-white rounded-lg border border-gray-200 shadow  transition-shadow duration-300 p-4 flex flex-col justify-between h-full @if($insurance->claim_ratings_count <= 0) disabled opacity-30 @else hover:shadow-lg @endif">
        <div class="grid grid-cols-12 gap-4 mb-4">
            <div class="col-span-2 pr-4">
                <div class="aspect-square w-12 rounded-full flex items-center justify-center text-white text-base font-bold" style="background-color: {{ $insurance->color ?? '#ccc' }};">
                    {{ strtoupper(substr( $insurance->initials, 0 ,4)) }}
                </div>
            </div>
            <div class="col-span-10">
                <h2 class="text-xl break-words font-semibold mb-2">
                    {{ $insurance->name }}
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
                @if($insurance->claim_ratings_count > 0)
                    <span class="font-medium">Bewertungen:</span>
                    <span class="text-gray-700">
                        {{ $insurance->claim_ratings_count ?? 0 }}
                    </span> 
                @endif
            </div>
        </div>
    </div>
</a>

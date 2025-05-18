<div>

<div class=" bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        {{-- Zusammenfassung --}}
        <div class="bg-white shadow rounded-lg p-6 mb-8">

            <p class="text-xl mb-3">Ø durschnitts Bewertung:             
                </p>
                <x-insurance.insurance-rating-stars :score="$average" />
            <p class="text-gray-600">{{ $totalCount }} Bewertungen insgesamt</p>
        </div>
    
        {{-- Einzelbewertungen --}}
        <div class="grid gap-4"
            wire:ignore>
            <div  >
                <div class="">
                    @foreach ($ratings as $rating)
                        <div class="swiper-slide h-full"  wire:key="rating-{{ $rating->id }}">
                            <x-claim-rating.claim-rating-card :rating="$rating" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
</div>

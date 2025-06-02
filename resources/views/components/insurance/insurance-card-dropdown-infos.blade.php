<div class="">
    <div class="px-4 py-1  bg-white border-b border-blue-200 flex items-center justify-between">
        <span class="text-gray-500 text-sm">Bewertungen: {{ $insurance->claim_ratings_count }}</span>
        <div>
            <button class="text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-1" @click="$dispatch('toggle-insurance-info', { slug: '{{ $insurance->slug }}' })">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="px-4 pb-2">
        <div class="flex items-center justify-between gap-2 mt-2 border-b border-gray-300 pb-2">
            <div>
                @if($insurance->claim_ratings_count > 0)
                    <x-insurance.insurance-rating-stars :score="$insurance->claim_ratings_avg_rating_score" />
                @else
                    <span class="text-gray-500">Keine Bewertungen</span>
                @endif
            </div>
            <span class="text-sm text-gray-700 py-0.5 px-1 bg-white border border-gray-300 rounded-lg">Ø Dauer: {{ $insurance->avgRatingDuration() ?? 29 }} Tage</span>
        </div>
    
        <div class="mt-4 p-2 bg-yellow-50 border border-yellow-500 text-yellow-800 rounded-lg flex items-center  gap-3 ">
            <svg class="w-6 h-6 mt-1 flex-none text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
            </svg>
            <div>
                <h3 class=" text-base">Noch keine detaillierte Auswertung</h3>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 mt-4">
            <a  href="{{ route('insurance.show-insurance', $insurance->slug) }}" class="text-blue-800 bg-gray-100 border border-gray-300 hover:bg-blue-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 text-center inline-flex items-center">
                Mehr erfahren
                <svg class="rtl:rotate-180 w-2.5 h-2.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        </div>    
    </div>
</div>
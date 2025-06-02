<div class="">
    <div class="px-4 py-1  bg-white border-b border-blue-200 flex items-center justify-between shadow">
        <span class="text-gray-500 text-sm">Bewertungen: {{ $insurance->claim_ratings_count }}</span>
<div class="flex items-center gap-1">
    <!-- Auswählen -->
    <button class="p-1 rounded-lg hover:bg-gray-100 text-blue-600 hover:text-blue-800"
            @click="$dispatch('toggle-select', { id: {{ $insurance->id }} })"
            title="Auswählen">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </button>

    <!-- Merkliste -->
    <button class="p-1 rounded-lg hover:bg-gray-100 text-pink-500 hover:text-pink-700"
            @click="$dispatch('toggle-favorite', { id: {{ $insurance->id }} })"
            title="Zur Merkliste hinzufügen">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 21l7-5 7 5V5a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
        </svg>
    </button>

    <!-- Benachrichtigen -->
    <button class="p-1 rounded-lg hover:bg-gray-100 text-yellow-500 hover:text-yellow-600"
            @click="$dispatch('toggle-follow', { id: {{ $insurance->id }} })"
            title="Benachrichtigen bei Updates">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 00-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
    </button>

    <!-- Vorhandener Info-Toggle -->
    <button class="text-blue-600 hover:text-blue-800 focus:outline-none rounded-lg p-1"
            @click="$dispatch('toggle-insurance-info', { slug: '{{ $insurance->slug }}' })"
            title="Details anzeigen/verstecken">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
    </button>
</div>

    </div>
    <div class="px-4 pb-2 inset-shadow-xs">
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
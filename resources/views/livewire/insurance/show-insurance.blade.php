<div>
    <div class="container mx-auto px-4 pt-12 py-6">
        <div class="">
            <div class="flex items-center mb-4">
                <div class="shrink-0 py-2 transition-all duration-200 flex ">
                    @if ($insurance->logo)
                        <img src="{{ asset('storage/' . $insurance->logo) }}"  alt=""  class=" h-6 mx-auto object-contain rounded">
                    @else
                        <div class=" w-min rounded flex items-center justify-center text-sm border px-1 font-medium shadow-sm" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                            {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
                        </div>
                    @endif
                </div>
                <div class="grow  min-w-0 py-2 pl-4 pr-1 transition-all duration-200">
                    <div>
                        <h2 class="text-base break-words  truncate text-ellipsis">
                            {{  $insurance->name }}
                        </h2>
                    </div>
                </div>
            </div>
            <p class="text-gray-600 mb-4">{{ $insurance->description }}</p>
            @if($insurance->detailInsuranceRatings()->count() > 0)
                <x-insurance.insurance-detail-insurance-ratings :detailInsuranceRating="$insurance->latestDetailInsuranceRating" />
            @else
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg flex items-start gap-3">
                    <svg class="w-6 h-6 mt-1 flex-none text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-base mb-1">Noch keine detaillierte Auswertung</h3>
                        <p class="text-sm">Für diese Versicherung liegen aktuell noch keine ausreichend bewerteten Fälle vor. Sobald erste Bewertungen eingegangen sind, wird hier eine Auswertung angezeigt.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="mt-12 bg-gray-50">
        <div class="container mx-auto px-4 pt-12 py-6 ">
            @if($insurance->ratings_count() > 0)
                <h2 class="flex items-center justify-center text-lg px-2 py-1 w-max mb-5">
                    <span class="w-max">Bewertungen</span>
                    <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                        {{ $insurance->ratings_count() }}
                    </span>
                </h2>
                <x-filter.filter-container>
                    <x-slot name="filters">
                        <div class="p-2">
                            <x-filter.filter-search-field wire:model.live="search" />
                        </div>
                    </x-slot>
                    <x-slot name="listContent">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($claimRatings as $claim_rating)
                                <x-claim-rating.claim-rating-card :rating="$claim_rating" />
                            @endforeach
                        </div>
                    </x-slot>
                </x-filter.filter-container>
            @endif
        </div>
    </div>
</div>

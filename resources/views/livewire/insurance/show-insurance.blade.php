<div>
    <div class="container mx-auto px-4 pt-12 py-6">
        <div class="">
            <div class="flex items-center mb-4">
                <div class="shrink-0 py-2 transition-all duration-200 flex ">
                    @if ($insurance->logo)
                    <div class="flex items-center space-x-1 relative">
                        <!-- Logo -->
                        <img src="{{ asset('storage/' . $insurance->logo) }}"
                            alt="Logo Versicherungs Anbieter"
                            class=" h-8 mx-auto object-contain rounded">
                        <!-- Info-Icon -->
                        <x-insurance.insurance-logo-disclaim />
                    </div>
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
                        <div class="p-2 mb-2">
                            <x-filter.filter-search-field wire:model.live="search" :label="'Suche'"/>
                        </div>
                        <div class="p-2 mb-2">
                            <label class="text-sm text-gray-400 px-2  mb-1 flex justify-left space-x-2 align-middle content-center">
                                <svg class="w-4 stroke-current stroke-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50">
                                    <path d="M 20 3 C 18.355469 3 17 4.355469 17 6 L 17 9 L 3 9 C 1.355469 9 0 10.355469 0 12 L 0 26.8125 C -0.0078125 26.875 -0.0078125 26.9375 0 27 L 0 44 C 0 45.644531 1.355469 47 3 47 L 47 47 C 48.644531 47 50 45.644531 50 44 L 50 12 C 50 10.355469 48.644531 9 47 9 L 33 9 L 33 6 C 33 4.355469 31.644531 3 30 3 Z M 20 5 L 30 5 C 30.5625 5 31 5.4375 31 6 L 31 9 L 19 9 L 19 6 C 19 5.4375 19.4375 5 20 5 Z M 3 11 L 47 11 C 47.5625 11 48 11.4375 48 12 L 48 26.84375 C 48 26.875 48 26.90625 48 26.9375 L 48 27 C 48 27.5625 47.5625 28 47 28 L 3 28 C 2.4375 28 2 27.5625 2 27 C 2.007813 26.9375 2.007813 26.875 2 26.8125 L 2 12 C 2 11.4375 2.4375 11 3 11 Z M 25 22 C 23.894531 22 23 22.894531 23 24 C 23 25.105469 23.894531 26 25 26 C 26.105469 26 27 25.105469 27 24 C 27 22.894531 26.105469 22 25 22 Z M 2 29.8125 C 2.316406 29.925781 2.648438 30 3 30 L 47 30 C 47.351563 30 47.683594 29.925781 48 29.8125 L 48 44 C 48 44.5625 47.5625 45 47 45 L 3 45 C 2.4375 45 2 44.5625 2 44 Z"></path>
                                </svg>                                
                                <span>Arten:</span>
                            </label>
                            <x-filter.filter-dropdown-checkbox wire:model.live="selectedInsuranceSubTypefilter"  :options="$insuranceSubTypes" />
                        </div>
                        <div class="p-2 mb-2">
                            <label class=" text-sm text-gray-400 px-2  mb-1 flex justify-left space-x-2 align-middle content-center">
                                <svg class="w-4 h-4 stroke-current stroke-2" viewBox="0 0 20 20">
                                    <path class="stroke-current" fill="none" stroke="1" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                                </svg>
                                <span>
                                    Mind. Ø Sterne
                                </span>
                            </label>
                            <select wire:model.live="minAvgScore"
                                    class="w-full px-3 py-2 border rounded-md text-sm border-blue-200">
                                <option value="">Keine Auswahl</option>
                                <option value=".1">0,5+</option>
                                <option value=".2">1+</option>
                                <option value=".4">2+</option>
                                <option value=".6">3+</option>
                                <option value=".8">4+</option>
                                <option value=".9">4,5+</option>
                            </select>
                        </div>
                        <div class="p-2 mb-2">
                            <label class="text-sm text-gray-400 px-2  mb-1 flex justify-left space-x-2 align-middle content-center">
                                <svg class="w-4 h-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40.2299 34.1955"><polygon  points="8.046 6.034 8.046 34.195 4.023 34.195 4.023 6.034 0 6.034 6.034 0 12.069 6.034 8.046 6.034"/><path  d="M14.0804,4.023V0H40.2299V4.023Zm0,10.0575v-4.023h20.115v4.023Zm0,20.115v-4.023h8.046v4.023Zm0-10.0575V20.115H28.1609v4.023Z"/></svg>
                                <span>Sortierung</span>
                            </label>
                            <select wire:model.live="sort"
                                    class="w-full px-3 py-2 pr-8 border rounded-md text-sm border-blue-200">
                                <option value="score_desc">Ø Sterne ↓</option>
                                <option value="score_asc">
                                    Ø Sterne ↑
                                </option>
                                <option value="ratings_desc">Bewertungen ↓</option>
                                <option value="ratings_asc">Bewertungen ↑</option>
                            </select>
                        </div>
                        <div class="p-2">
                            <x-buttons.button-basic wire:click="resetFilters" class="mt-4 text-sm text-blue-600 w-full">
                                Filter zurücksetzen
                            </x-buttons.button-basic>
                        </div>
                    </x-slot>
                    <x-slot name="listContent">
                        @if($this->isFiltered)
                            <div class="mb-4 text-sm text-gray-600">
                                {{ $claimRatings->total() }} Bewertungen gefunden.
                            </div>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($claimRatings as $claim_rating)
                                <x-claim-rating.claim-rating-card :rating="$claim_rating" />
                            @endforeach
                        </div>
                        @if($claimRatings->count() >= $perPage * $pages)
                            <div class="mt-6 text-center">
                                <x-buttons.button-basic wire:click="loadMore">
                                    Mehr laden
                                </x-buttons.button-basic>
                            </div>
                        @endif
                    </x-slot>
                </x-filter.filter-container>
            @endif
        </div>
    </div>
</div>

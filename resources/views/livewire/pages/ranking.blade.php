<div class="app-page app-shell app-ranking-page">
    <div class="mb-6">
    <div class="pb-6">
        <div>
            <h2 class="app-heading mb-3">Ranking & Auszeichnungen filtern</h2>
            <p class="mb-4 max-w-lg text-[var(--muted)] text-base">
                Hier können Sie das Ranking und die Auszeichnungen der Versicherungen nach verschiedenen Versicherungsarten filtern. Wählen Sie einfach die gewünschte Versicherungsart aus, um die Ergebnisse entsprechend anzupassen.
            </p>
        </div>
    </div>
    </div>
    <div class="pt-2">
        <x-filter.filter-container>
            <x-slot name="filters">
                <div class="p-2 mb-2" >
                    <label class="text-sm text-[var(--muted)] px-2  mb-1 flex justify-left space-x-2 align-middle content-center">
                        <svg class="w-4 stroke-current stroke-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50">
                        <path d="M 20 3 C 18.355469 3 17 4.355469 17 6 L 17 9 L 3 9 C 1.355469 9 0 10.355469 0 12 L 0 26.8125 C -0.0078125 26.875 -0.0078125 26.9375 0 27 L 0 44 C 0 45.644531 1.355469 47 3 47 L 47 47 C 48.644531 47 50 45.644531 50 44 L 50 12 C 50 10.355469 48.644531 9 47 9 L 33 9 L 33 6 C 33 4.355469 31.644531 3 30 3 Z M 20 5 L 30 5 C 30.5625 5 31 5.4375 31 6 L 31 9 L 19 9 L 19 6 C 19 5.4375 19.4375 5 20 5 Z M 3 11 L 47 11 C 47.5625 11 48 11.4375 48 12 L 48 26.84375 C 48 26.875 48 26.90625 48 26.9375 L 48 27 C 48 27.5625 47.5625 28 47 28 L 3 28 C 2.4375 28 2 27.5625 2 27 C 2.007813 26.9375 2.007813 26.875 2 26.8125 L 2 12 C 2 11.4375 2.4375 11 3 11 Z M 25 22 C 23.894531 22 23 22.894531 23 24 C 23 25.105469 23.894531 26 25 26 C 26.105469 26 27 25.105469 27 24 C 27 22.894531 26.105469 22 25 22 Z M 2 29.8125 C 2.316406 29.925781 2.648438 30 3 30 L 47 30 C 47.351563 30 47.683594 29.925781 48 29.8125 L 48 44 C 48 44.5625 47.5625 45 47 45 L 3 45 C 2.4375 45 2 44.5625 2 44 Z"></path>
                        </svg>                                
                        <span>Arten:</span>
                    </label>
                    <x-filter.filter-dropdown-checkbox wire:model.live="selectedInsuranceSubTypefilter" :options="$insuranceSubTypes" />
                </div>
                <div class="p-2 mb-2" >
                    <label class=" text-sm text-[var(--muted)] px-2  mb-1 flex justify-left space-x-2 align-middle content-center">
                        <svg class="w-4 h-4 stroke-current stroke-2" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>
                            Aspekt
                        </span>
                    </label>
                    <select wire:model.live="selectedAspect" aria-label="Aspekt" class="app-control min-h-12 w-full rounded-xl border border-[var(--line)] bg-[var(--surface)] px-3 py-2 text-sm text-[var(--ink)]">
                        <option value="allgemein">Allgemein</option>
                        <option value="geschwindigkeit">Geschwindigkeit</option>
                        <option value="kunden_service">Kunden Service</option>
                        <option value="fairness">Fairness</option>
                        <option value="transparenz">Transparenz</option>
                    </select>
                </div>
                <div class="p-2">
                    <x-buttons.button-basic wire:click="resetFilters" class="app-button-secondary mt-4 w-full text-sm">
                        Filter zurücksetzen
                    </x-buttons.button-basic>
                </div>
            </x-slot>
            <x-slot name="listContent">
                <div class="">
                
                    <div class="max-w-4xl">
                        <div class="flex flex-col space-y-4">
                            <p class="text-xs text-[var(--muted)]" role="status" aria-live="polite">{{ $totalInsurances }} Versicherer · Sortiert nach: {{ $aspectLabel }}</p>
                            @forelse ($allInsurances as $insurance)
                            <div wire:key="ranking-insurance-{{ $insurance->id }}" class="flex items-start justify-between gap-3 mb-2">
                                    <div class="w-9 shrink-0 pt-5 flex items-center justify-center " >
                                        @php
                                            switch ($loop->iteration) {
                                                case 1:
                                                    $classes = '';
                                                    break;
                                                case 2:
                                                    $classes = '';
                                                    break;
                                                case 3:
                                                    $classes = '';
                                                    break;
                                                default:
                                                    $classes = 'bg-[var(--surface-soft)] text-[var(--muted)]';
                                                    break;
                                            }
                                        @endphp
                                        <span class="inline-flex items-center justify-center rounded-full text-lg font-semibold  w-8 h-8 {{ $classes }}">
                                            @if($loop->iteration == 1) 
                                                <img src="{{ asset('/site-images/place1.png') }}" alt="">                                    
                                            @elseif($loop->iteration == 2) 
                                                <img src="{{ asset('/site-images/place2.png') }}" alt="">                                    
                                            @elseif($loop->iteration == 3) 
                                                <img src="{{ asset('/site-images/place3.png') }}" alt="">                                    
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="mb-2 text-xs text-[var(--muted)]">{{ $aspectLabel }}: <span class="font-semibold text-[var(--ink)]">{{ number_format($insurance->ranking_score * 5, 1, ',', '.') }} / 5</span></div>
                                        <x-insurance.insurance-card :insurance="$insurance" :selectedInsuranceSubTypeIds="$selectedSubtypeIds" />
                                    </div>
                                </div>
                            @empty
                                <div class="app-panel py-10 text-center text-sm text-[var(--muted)]">Für diese Auswahl liegen noch keine auswertbaren Bewertungen vor.</div>
                            @endforelse
                            @if($hasMoreInsurances)
                                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-[var(--line)] pt-5">
                                    <span class="text-xs text-[var(--muted)]">{{ $allInsurances->count() }} von {{ $totalInsurances }} Versicherern</span>
                                    <button type="button" wire:key="ranking-load-more" wire:click="loadMore" wire:loading.attr="disabled" wire:target="loadMore" class="app-button-secondary min-h-12 px-6">
                                        <span wire:loading.remove wire:target="loadMore">Weitere Versicherer laden</span>
                                        <span wire:loading wire:target="loadMore">Wird geladen …</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
        </x-slot>
        </x-filter.filter-container>
    </div>
</div>

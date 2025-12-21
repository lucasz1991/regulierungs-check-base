<div class="min-h-screen ">

    {{-- HERO / ÜBERBLICK --}}
    <div class="container mx-auto px-4 pb-8">
        <div class="">

            {{-- Top row: Logo + Name --}}
            <div class="md:flex items-start gap-4">
                <div class="shrink-0 max-md:mb-6">
                    @if ($insurance->logo)
                        <div class="flex items-center space-x-2 relative">
                            <div class="bg-white p-2  rounded-2xl">
                                <img src="{{ asset('storage/' . $insurance->logo) }}"
                                     alt="Logo Versicherungs Anbieter"
                                     class="h-10 md:h-12 object-contain" loading="lazy">
                            </div>
                            <x-insurance.insurance-logo-disclaim />
                        </div>
                    @else
                        <div class="w-min rounded-lg flex items-center justify-center text-sm border px-2 py-1 font-semibold shadow-sm"
                             style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                            {{ strtoupper(substr($insurance->initials, 0, 8)) }}
                        </div>
                    @endif
                </div>

                <div class="min-w-0">
                    <h1 class="text-2xl md:text-4xl font-semibold text-white leading-tight">
                        {{ $insurance->name ?? 'Versicherung' }}
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-white">
                        {{ $insurance->description }}
                    </p>
                </div>
            </div>
            @php
                // Werte vorbereiten (robust)
                $days = (int) round($insurance->avgRatingDurationBySubtype($subTypeFilterSubType->id ?? null));
                $scoreRaw = $insurance->latestDetailInsuranceRating->total_score ?? null;
                $score5 = $scoreRaw !== null ? round($scoreRaw * 5, 1) : null;

                $count = (int) ($insurance->published_claimRatingsCountBySubtype($subTypeFilterSubType->id ?? null) ?? 0);

                // Kreis-Füllstände (0..100)
                // 1) Dauer: je weniger Tage, desto besser (hier grob: 0 Tage => 100%, 120+ Tage => 0%)
                $daysCap = 120;
                $daysPct = max(0, min(100, (int) round((1 - min($days, $daysCap) / $daysCap) * 100)));

                // 2) Score: 0..5 => 0..100
                $scorePct = $score5 !== null ? (int) round(($score5 / 5) * 100) : 0;

                // 3) Bewertungen: Sättigung (z.B. 200 Bewertungen => 100%)
                $countCap = 200;
                $countPct = max(0, min(100, (int) round(min($count, $countCap) / $countCap * 100)));
            @endphp
            <div class="hidden md:block">
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    
                    {{-- KPI 1: Ø Bearbeitungsdauer --}}
                    <div class="rounded-2xl bg-white/80 border border-white/10 shadow p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-700 white flex items-center gap-2">
                                    <i class="fal fa-clock  text-primary fa-2x"></i>
                                    <span>Ø Bearbeitungsdauer</span>
                                </p>
                            </div>
    
                            {{-- Kreis --}}
                            <div class="relative w-20 h-20 shrink-0">
                                <div class="absolute inset-0 rounded-full bg-gray-100"></div>
                                <div
                                    class="absolute inset-0 rounded-full"
                                    style="background: conic-gradient(#2563eb {{ $daysPct }}%, #e5e7eb 0);"
                                ></div>
                                <div class="absolute inset-[6px] rounded-full bg-white flex flex-col items-center justify-center text-center">
                                    <div class="text-lg font-semibold text-gray-900 leading-none">{{ $days }}</div>
                                    <div class="text-[10px] text-gray-500 leading-none mt-1">Tage</div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    {{-- KPI 2: Gesamtbewertung --}}
                    <div class="rounded-2xl bg-white/80 border border-white/10 shadow p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-700 flex items-center gap-2">
                                    <i class="fal fa-star text-primary fa-2x"></i>
                                    <span>Gesamtbewertung</span>
                                </p>
                            </div>
    
                            {{-- Kreis --}}
                            <div class="relative w-20 h-20 shrink-0">
                                <div class="absolute inset-0 rounded-full bg-gray-100"></div>
                                <div
                                    class="absolute inset-0 rounded-full"
                                    style="background: conic-gradient(#f59e0b {{ $scorePct }}%, #e5e7eb 0);"
                                ></div>
                                <div class="absolute inset-[6px] rounded-full bg-white flex  items-center justify-center text-center">
                                    <div class="text-lg font-semibold text-gray-900 leading-none">
                                        {{ $score5 !== null ? number_format($score5, 1, ',', '.') : '–' }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 leading-none mt-1">/ 5</div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    {{-- KPI 3: Bewertungen --}}
                    <div class="rounded-2xl bg-white/80 border border-white/10 shadow p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-700 flex items-center gap-2">
                                    <i class="fal fa-comments  text-primary fa-2x"></i>
                                    <span>Bewertungen</span>
                                </p>
                            </div>
    
                            {{-- Kreis --}}
                            <div class="relative w-20 h-20 shrink-0">
                                <div class="absolute inset-0 rounded-full bg-gray-100"></div>
                                <div
                                    class="absolute inset-0 rounded-full"
                                    style="background: conic-gradient(#10b981 {{ $countPct }}%, #e5e7eb 0);"
                                ></div>
                                <div class="absolute inset-[6px] rounded-full bg-white flex flex-col items-center justify-center text-center">
                                    <div class="text-lg font-semibold text-gray-900 leading-none">{{ $count }}</div>
                                    <div class="text-[10px] text-gray-500 leading-none mt-1">gesamt</div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>
    
    
                {{-- DASHBOARD: Auswertung in Cards (hell) --}}
                <div class="mt-6">
                    @if($insurance->detailInsuranceRatings()->count() > 0)
                        <div class="">
                            {{-- große Card links (2/3) --}}
                            <div class="lg:col-span-2 rounded-2xl bg-white/80 border border-white/30 shadow p-5">
                                <x-insurance.insurance-detail-insurance-ratings
                                    :detailInsuranceRating="$insurance->latestDetailInsuranceRating"
                                    :insurance="$insurance"
                                />
                            </div>
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg flex items-start gap-3">
                            <svg class="w-6 h-6 mt-1 flex-none text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-base mb-1 ">Noch keine detaillierte Auswertung</h3>
                                <p class="text-sm ">
                                    Für diese Versicherung liegen aktuell noch keine ausreichend bewerteten Fälle vor.
                                    Sobald erste Bewertungen eingegangen sind, wird hier eine Auswertung angezeigt.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
            @php
                $detailInsuranceRating =  $insurance->latestDetailInsuranceRating;
            @endphp            
            <div class="md:hidden mt-8">
                <div
                    x-data="{ swiper: null }"
                    x-init="
                        swiper = new Swiper($refs.mobileSwiper, {
                            slidesPerView: 1,
                            spaceBetween: 20,
                            autoHeight: false,
                            pagination: {
                                el: $refs.pagination,
                                clickable: true,
                            },
                        });
                    "
                    class="relative"
                >
                    {{-- SWIPER --}}
                    <div class="swiper" x-ref="mobileSwiper">
                        <div class="swiper-wrapper">

                            {{-- ===================================================== --}}
                            {{-- SLIDE 1: KPIs (ALLE 3 in EINEM Slide) --}}
                            {{-- ===================================================== --}}
                            <div class="swiper-slide">
                                <div class="rounded-2xl bg-white/80 border border-white/10 shadow p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                            <i class="fal fa-chart-line text-blue-600"></i>
                                            <span>Übersicht</span>
                                        </h3>
                                    </div>

                                    <div class="grid grid-cols-1 gap-2">
                                        {{-- KPI 1 --}}
                                        <div class="rounded-2xl bg-white/95 border border-white/10 shadow p-3">
                                            <div class="flex items-start justify-between gap-4">
                                                <p class="text-sm text-gray-700 flex items-center gap-2">
                                                    <i class="fal fa-clock text-primary fa-lg"></i>
                                                    Ø Bearbeitungsdauer
                                                </p>

                                                <div class="relative  w-16 h-16  shrink-0">
                                                    <div class="absolute inset-0 rounded-full bg-gray-100"></div>
                                                    <div
                                                        class="absolute inset-0 rounded-full"
                                                        style="background: conic-gradient(#2563eb {{ $daysPct }}%, #e5e7eb 0);"
                                                    ></div>
                                                    <div class="absolute inset-[6px] rounded-full bg-white flex flex-col items-center justify-center">
                                                        <div class="text-lg font-semibold">{{ $days }}</div>
                                                        <div class="text-[10px] text-gray-500">Tage</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- KPI 2 --}}
                                        <div class="rounded-2xl bg-white/95 border border-white/10 shadow p-3">
                                            <div class="flex items-start justify-between gap-4">
                                                <p class="text-sm text-gray-700 flex items-center gap-2">
                                                    <i class="fal fa-star text-amber-500 fa-lg"></i>
                                                    Gesamtbewertung
                                                </p>

                                                <div class="relative  w-16 h-16  shrink-0">
                                                    <div class="absolute inset-0 rounded-full bg-gray-100"></div>
                                                    <div
                                                        class="absolute inset-0 rounded-full"
                                                        style="background: conic-gradient(#f59e0b {{ $scorePct }}%, #e5e7eb 0);"
                                                    ></div>
                                                    <div class="absolute inset-[6px] rounded-full bg-white flex items-center justify-center">
                                                        <div class="text-lg font-semibold">
                                                            {{ $score5 !== null ? number_format($score5, 1, ',', '.') : '–' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- KPI 3 --}}
                                        <div class="rounded-2xl bg-white/95 border border-white/10 shadow p-3">
                                            <div class="flex items-start justify-between gap-4">
                                                <p class="text-sm text-gray-700 flex items-center gap-2">
                                                    <i class="fal fa-comments text-emerald-500 fa-lg"></i>
                                                    Bewertungen
                                                </p>

                                                <div class="relative  w-16 h-16  shrink-0">
                                                    <div class="absolute inset-0 rounded-full bg-gray-100"></div>
                                                    <div
                                                        class="absolute inset-0 rounded-full"
                                                        style="background: conic-gradient(#10b981 {{ $countPct }}%, #e5e7eb 0);"
                                                    ></div>
                                                    <div class="absolute inset-[6px] rounded-full bg-white flex flex-col items-center justify-center">
                                                        <div class="text-lg font-semibold">{{ $count }}</div>
                                                        <div class="text-[10px] text-gray-500">gesamt</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ===================================================== --}}
                            {{-- SLIDE 2: Scorings --}}
                            {{-- ===================================================== --}}
                            <div class="swiper-slide">
                                <div class="">

                                    @if($insurance->detailInsuranceRatings()->count() > 0)
                                        <div class="rounded-2xl bg-white/80 border border-white/10 shadow p-3 pb-5">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-xs font-semibold text-gray-900 flex items-center gap-2">
                                                    <i class="fal fa-chart-bar text-blue-600"></i>
                                                    Scorings
                                                </h3>
                                                <span class="text-[10px] text-gray-500 rounded-full bg-gray-100 px-2 py-0.5">
                                                    Ø / 5
                                                </span>
                                            </div>

                                            <div class="space-y-6 my-3">
                                                {{-- Scoring --}}
                                                <div class="rounded-xl bg-white p-2.5 shadow-sm border border-gray-100 flex flex-wrap items-center justify-between">
                                                    <span class="text-xs text-gray-700 flex items-center gap-2">
                                                        <i class="fal fa-clock text-primary "></i>
                                                                <span>Dauer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                    </span>
                                                    <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->speed" :size="'md'"/>
                                                </div>

                                                <div class="rounded-xl bg-white p-2.5 shadow-sm border border-gray-100 flex flex-wrap items-center justify-between">
                                                    <span class="text-xs text-gray-700 flex items-center gap-2">
                                                        <i class="fal fa-headset text-primary "></i>
                                                        Kundenservice
                                                    </span>
                                                    <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->communication" :size="'md'"/>
                                                </div>

                                                <div class="rounded-xl bg-white p-2.5 shadow-sm border border-gray-100 flex flex-wrap items-center justify-between">
                                                    <span class="text-xs text-gray-700 flex items-center gap-2">
                                                        <i class="fal fa-balance-scale text-primary "></i>
                                                                <span>Fairness&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                    </span>
                                                    <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->fairness" :size="'md'"/>
                                                </div>

                                                <div class="rounded-xl bg-white p-2.5 shadow-sm border border-gray-100 flex flex-wrap items-center justify-between">
                                                    <span class="text-xs text-gray-700 flex items-center gap-2">
                                                        <i class="fal fa-eye text-primary "></i>
                                                                <span>Transparenz&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                    </span>
                                                    <x-insurance.insurance-rating-stars :score="$detailInsuranceRating->transparency" :size="'md'"/>
                                                </div>
                                            </div>
                                        </div>



                                    @else
                                        <div class="rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 flex gap-3">
                                            <i class="fal fa-exclamation-triangle mt-0.5"></i>
                                            <div>
                                                <p class="font-semibold">Noch keine Auswertung</p>
                                                <p class="text-sm">
                                                    Sobald erste Bewertungen eingehen, erscheint hier eine Auswertung.
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            {{-- ===================================================== --}}
                            {{-- SLIDE 3: Kommentar --}}
                            {{-- ===================================================== --}}
                            <div class="swiper-slide">
                                <div class="">

                                    @if($insurance->detailInsuranceRatings()->count() > 0)
                                        <div class="rounded-2xl bg-white/80 border border-white/10 shadow p-3">
                                            <div class="flex items-center gap-2 mb-3">
                                                <h3 class="text-xs font-semibold text-gray-900 flex items-center gap-2">
                                                    <i class="fal fa-comment-alt text-blue-600"></i>
                                                    Zusammenfassung
                                                </h3>
                                            </div>

                                            <div class="rounded-xl bg-white p-3 shadow-sm border border-gray-100 text-sm text-gray-700 leading-relaxed">
                                                <x-ui.read-more-typewriter
                                                    :text="$detailInsuranceRating->ai_comment ?: 'Kein Kommentar vorhanden.'"
                                                    limitPx="190"
                                                    speed="1"
                                                    heightAnim="4000"
                                                />
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 flex gap-3">
                                            <i class="fal fa-exclamation-triangle mt-0.5"></i>
                                            <div>
                                                <p class="font-semibold">Noch keine Auswertung</p>
                                                <p class="text-sm">
                                                    Sobald erste Bewertungen eingehen, erscheint hier eine Auswertung.
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div x-ref="pagination" class="bg-white/80 rounded-xl swiper-pagination mt-4 !relative !bottom-0 !w-[70px] mx-auto"></div>

                </div>
            </div>
        </div>
    </div>

     <div class="mt-6">
        <div class="container mx-auto px-4 pt-6 py-6 ">
            @if($insurance->published_ratings_count() > 0)
                <h2 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <span class="w-max text-white">Bewertungen</span>
                    <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-7 leading-none">
                        {{ $insurance->published_ratings_count() }}
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
                            <div class="mb-4 text-sm text-white">
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

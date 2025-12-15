<div
    x-data="{
        loading: false,
        async loadMore() {
            if (this.loading) return;
            const y = window.scrollY;
            this.loading = true;
            document.activeElement?.blur();
            await $wire.loadMore();
            requestAnimationFrame(() => {
                window.scrollTo({ top: y, left: 0, behavior: 'auto' });
                this.loading = false;
            });
        }
    }"
>
    <section class="pt-2">
        <div
            x-data="{
                showFilters: $persist(false),
                __openedByUser: false
            }"
        >
            <div class="container mx-auto p-4 pb-8">

                {{-- Mobile Filter Toggle --}}
                <div x-show="!$store.nav.isScreenXl" class="mb-4 max-xl:flex max-xl:justify-end" wire:ignore>
                    <button
                        type="button"
                        @click="__openedByUser = true; showFilters = !showFilters"
                        class="text-sm text-blue-600 hover:underline p-2 rounded-full bg-gray-200 mr-3 flex items-center justify-center shadow-xl shadow-gray-900/5 border border-gray-300"
                    >
                        <svg
                            :class="{ 'xl:rotate-180 max-xl:rotate-0': !showFilters, 'max-xl:rotate-180 xl:rotate-0': showFilters }"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-blue-600 transform transition-all mr-1"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>

                        <span class="max-md:hidden mr-3">Filter</span>

                        <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 40.2299 36.2069">
                            <path d="M0,6.0345V2.0115H7.8921v4.023Zm12.0742,0V2.0115H40.2299v4.023ZM0,20.115V16.092H27.88961v4.023Zm32.6158,0V16.092h7.6141v4.023ZM0,34.1955v-4.023H18.02461v4.023Zm22.39561,0v-4.023H40.2299v4.023Z"/>
                            <circle cx="10.0575" cy="4.023" r="4.023"/>
                            <circle cx="30.1724" cy="18.1035" r="4.023"/>
                            <path d="M20.115,28.161a4.023,4.023,0,1,1-4.023,4.023A4.0229,4.0229,0,0,1,20.115,28.161Z"/>
                        </svg>
                    </button>
                </div>

                <div class="xl:grid xl:grid-cols-12 xl:gap-6">

                    {{-- FILTER SIDEBAR --}}
                    <div
                        x-show="showFilters || $store.nav.isScreenXl"
                        x-cloak
                        wire:ignore
                        class="filter-sidebar xl:col-span-2 max-xl:absolute max-xl:right-4 z-10"
                    >
                        {{-- Overlay (Mobile) --}}
                        <div
                            x-show="showFilters && !$store.nav.isScreenXl"
                            x-transition
                            class="max-xl:fixed xl:hidden inset-0 transform transition-all"
                            x-on:click="showFilters = false"
                        >
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        {{-- Panel --}}
                        <div
                            x-show="showFilters || $store.nav.isScreenXl"
                            x-transition
                            x-ref="filterPanel"
                            class="relative flex w-full max-w-[20rem] flex-col rounded-xl bg-white p-2 text-gray-700 shadow-xl shadow-gray-900/5 z-20"
                        >
                            <div class="p-2 mb-2">
                                <x-filter.filter-search-field wire:model.live="search" :label="'Suche'"/>
                            </div>

                            <div class="p-2 mb-2">
                                <label class="text-sm text-gray-400 px-2 mb-1 flex justify-left space-x-2 align-middle content-center">
                                    <span>Arten:</span>
                                </label>
                                <x-filter.filter-dropdown-checkbox
                                    wire:model.live="selectedInsuranceSubTypefilter"
                                    :options="$insuranceSubTypes"
                                />
                            </div>

                            <div class="p-2 mb-2">
                                <label class="text-sm text-gray-400 px-2 mb-1 flex justify-left space-x-2 align-middle content-center">
                                    <span>Mind. Ø Sterne</span>
                                </label>

                                <select wire:model.live="minAvgScore" class="w-full px-3 py-2 border rounded-md text-sm border-blue-200">
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
                                <label class="text-sm text-gray-400 px-2 mb-1 flex justify-left space-x-2 align-middle content-center">
                                    <span>Sortierung</span>
                                </label>

                                <select wire:model.live="sort" class="w-full px-3 py-2 pr-8 border rounded-md text-sm border-blue-200">
                                    <option value="score_desc">Ø Sterne ↓</option>
                                    <option value="score_asc">Ø Sterne ↑</option>
                                    <option value="count_desc">Bewertungen ↓</option>
                                    <option value="count_asc">Bewertungen ↑</option>
                                </select>
                            </div>

                            <div class="p-2">
                                <x-buttons.button-basic wire:click="resetFilters" class="mt-4 text-sm text-blue-600 w-full" type="button">
                                    Filter zurücksetzen
                                </x-buttons.button-basic>
                            </div>
                        </div>
                    </div>

                    {{-- LIST CONTENT --}}
                    <div
                        class="filter-sidebar"
                        :class="showFilters || ! $store.nav.isMobile ? 'xl:col-span-10' : 'xl:col-span-12'"
                        x-cloak
                        x-transition
                    >
                        @if($this->isFiltered)
                            <div class="mb-4 text-sm text-white">
                                {{ $insurances->count() }} Anbieter geladen.
                            </div>
                        @endif

                        <div wire:key="insurances-list" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($insurances as $insurance)
                                <div wire:key="insurance-{{ $insurance->id }}">

                                    <x-insurance.insurance-card
                                        :insurance="$insurance"
                                        :isSubTypeFilter="$isSubTypeFilter"
                                        :subTypeFilterSubType="$subTypeFilterSubType"
                                    />
                                </div>
                            @endforeach
                        </div>

                        @if(!$insurances->count())
                            <div class="text-center py-10 text-gray-500">
                                Keine Versicherungen gefunden.
                            </div>
                        @endif

                        @if($insurances->count() >= $perPage * $pages)
                            {{-- ✅ Livewire darf den Button NICHT morphen (Focus bleibt stabil) --}}
                            <div class="mt-6 text-center" wire:ignore>
                                <button
                                    type="button"
                                    class="px-4 py-2 rounded-lg border bg-white"
                                    tabindex="-1"
                                    onmousedown="event.preventDefault()"
                                    x-on:click.prevent="loadMore()"
                                    :disabled="loading"
                                >
                                    Mehr laden
                                </button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

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
    <section class="">
        <div
            x-data="{
                showFilters: $persist(false),
                __openedByUser: false
            }"
        >
            <div class="container mx-auto px-4 pb-8">

                                <x-filter.filter-container>
                    <x-slot name="filters">
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
                    </x-slot>
                    <x-slot name="listContent">
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
                            <div class="text-center py-10 text-white">
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
                        </x-slot>
                    </x-filter.filter-container>
        </div>
        </div>
    </section>
</div>

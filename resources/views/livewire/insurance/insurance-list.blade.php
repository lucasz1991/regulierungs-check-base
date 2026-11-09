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
    <section class="app-catalog-page app-page">
        <div
            x-data="{
                showFilters: $persist(false),
                __openedByUser: false
            }"
        >
            <div class="app-shell pb-8">

                                <x-filter.filter-container>
                    <x-slot name="filters">
                            <div class="p-2 mb-2">
                                <x-filter.filter-search-field wire:model.live="search" :label="'Suche'"/>
                            </div>

                            <div class="p-2 mb-2">
                                <label class="text-sm text-[var(--muted)] px-2 mb-1 flex justify-left space-x-2 align-middle content-center">
                                    <span>Typen:</span>
                                </label>
                                <x-filter.filter-dropdown-checkbox
                                    wire:model.live="selectedInsuranceTypefilter"
                                    :options="$insuranceTypes"
                                />
                            </div>

                            <div class="p-2 mb-2">
                                <label class="text-sm text-[var(--muted)] px-2 mb-1 flex justify-left space-x-2 align-middle content-center">
                                    <span>Unterarten:</span>
                                </label>
                                <div wire:key="insurance-list-subtype-filter-{{ $insuranceSubTypes->pluck('id')->implode('-') }}">
                                    <x-filter.filter-dropdown-checkbox
                                        wire:model.live="selectedInsuranceSubTypefilter"
                                        :options="$insuranceSubTypes"
                                    />
                                </div>
                            </div>

                            <div class="p-2 mb-2">
                                <label class="text-sm text-[var(--muted)] px-2 mb-1 flex justify-left space-x-2 align-middle content-center">
                                    <span>Mind. Ø Sterne</span>
                                </label>

                                <select wire:model.live="minAvgScore" class="app-control min-h-12 w-full rounded-xl border border-[var(--line)] bg-[var(--surface)] px-3 py-2 text-sm text-[var(--ink)]">
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
                                <label class="text-sm text-[var(--muted)] px-2 mb-1 flex justify-left space-x-2 align-middle content-center">
                                    <span>Sortierung</span>
                                </label>

                                <select wire:model.live="sort" class="app-control min-h-12 w-full rounded-xl border border-[var(--line)] bg-[var(--surface)] px-3 py-2 pr-8 text-sm text-[var(--ink)]">
                                    <option value="score_desc">Ø Sterne ↓</option>
                                    <option value="score_asc">Ø Sterne ↑</option>
                                    <option value="count_desc">Bewertungen ↓</option>
                                    <option value="count_asc">Bewertungen ↑</option>
                                </select>
                            </div>

                            <div class="p-2">
                                <x-buttons.button-basic wire:click="resetFilters" class="app-button-secondary mt-4 w-full text-sm" type="button">
                                    Filter zurücksetzen
                                </x-buttons.button-basic>
                            </div>
                    </x-slot>
                    <x-slot name="listContent">
                        @if($this->isFiltered)
                            <div class="mb-4 text-sm text-[var(--ink)]">
                                {{ $insurances->count() }} Anbieter geladen.
                            </div>
                        @endif

                        <div wire:key="insurances-list" class="mx-auto grid grid-cols-1 gap-5 lg:grid-cols-2">
                            @foreach($insurances as $insurance)
                                <div wire:key="insurance-{{ $insurance->id }}">

                                    <x-insurance.insurance-card
                                        :insurance="$insurance"
                                        :rank="$loop->iteration"
                                        :isSubTypeFilter="$isSubTypeFilter"
                                        :subTypeFilterSubType="$subTypeFilterSubType"
                                        :selectedInsuranceTypeIds="$selectedInsuranceTypeIds"
                                        :selectedInsuranceSubTypeIds="$selectedInsuranceSubTypeIds"
                                        :selectedInsuranceTypeSubtypeIds="$selectedInsuranceTypeSubtypeIds"
                                    />
                                </div>
                            @endforeach
                        </div>

                        @if(!$insurances->count())
                            <div class="text-center py-10 text-[var(--ink)]">
                                Keine Versicherungen gefunden.
                            </div>
                        @endif

                        @if($insurances->count() >= $perPage * $pages)
                            {{-- ✅ Livewire darf den Button NICHT morphen (Focus bleibt stabil) --}}
                            <div class="mt-6 text-center" wire:ignore>
                                <button
                                    type="button"
                                    class="app-button-secondary min-h-12 px-6"
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

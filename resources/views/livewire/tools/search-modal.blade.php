<div
    x-data="{
        openSearchMenu: false,
        moveSearchFocus(event, direction) {
            const panel = event.target.closest('[role=dialog]');
            const results = Array.from(panel.querySelectorAll('[data-search-result]'));
            if (!results.length) return;
            const index = results.indexOf(document.activeElement);
            if (index === 0 && direction < 0) {
                panel.querySelector('#search-dropdown').focus();
                return;
            }
            results[(index + direction + results.length) % results.length].focus();
        }
    }"
    x-init="$watch('openSearchMenu', open => { $dispatch('homepage-search-state', { open }); if (open) $nextTick(() => document.getElementById('search-dropdown')?.focus()); })"
    @homepage-search-open.window="openSearchMenu = true"
    @keydown.window.escape="openSearchMenu = false"
    class="h-full"
>
    @if($showTrigger)
        <button
            type="button"
            @click.prevent="openSearchMenu = !openSearchMenu"
            class="rc-search-trigger"
            aria-label="Suche öffnen"
            aria-controls="Search-menü"
            :aria-expanded="String(openSearchMenu)"
        >
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <circle cx="10.5" cy="10.5" r="6.5" />
                <path stroke-linecap="round" d="m15.5 15.5 4.5 4.5" />
            </svg>
        </button>
    @endif

    <template x-teleport="#megamenu">
        <div
            id="Search-menü"
            class="rc-search-overlay"
            :inert="!($store.nav.showNav && openSearchMenu)"
            x-trap.inert.noscroll.noautofocus="$store.nav.showNav && openSearchMenu"
        >
            <div
                class="rc-search-backdrop"
                x-show="$store.nav.showNav && openSearchMenu"
                x-transition.opacity.duration.200ms
                @click="openSearchMenu = false"
                aria-hidden="true"
                x-cloak
            ></div>

            <section
                role="dialog"
                aria-label="Suche"
                aria-modal="true"
                aria-describedby="rc-search-hint"
                :aria-hidden="!($store.nav.showNav && openSearchMenu)"
                class="rc-search-panel"
                x-show="$store.nav.showNav && openSearchMenu"
                x-transition:enter="rc-search-enter"
                x-transition:enter-start="rc-search-hidden"
                x-transition:enter-end="rc-search-visible"
                x-transition:leave="rc-search-leave"
                x-transition:leave-start="rc-search-visible"
                x-transition:leave-end="rc-search-hidden"
                x-cloak
            >
                <header class="rc-search-heading">
                    <div>
                        <span class="rc-search-eyebrow">REGULIERUNGS-CHECK</span>
                        <h2>Was möchtest du finden?</h2>
                    </div>
                    <button type="button" class="rc-search-close" @click="openSearchMenu = false" aria-label="Suche schließen">
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
                        </svg>
                    </button>
                </header>

                <div class="rc-search-field">
                    <svg class="rc-search-magnifier" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <circle cx="10.5" cy="10.5" r="6.5" />
                        <path stroke-linecap="round" d="m15.5 15.5 4.5 4.5" />
                    </svg>
                    <input
                        type="search"
                        id="search-dropdown"
                        x-ref="searchInput"
                        aria-label="Versicherungen und Informationen suchen"
                        aria-describedby="rc-search-hint"
                        aria-controls="rc-search-results"
                        wire:model.live="query"
                        placeholder="Versicherer oder Information suchen …"
                        autocomplete="off"
                        spellcheck="false"
                        @keydown.arrow-down.prevent="moveSearchFocus($event, 1)"
                    >
                    <button
                        type="button"
                        class="rc-search-clear"
                        aria-label="Suchbegriff löschen"
                        wire:click="$set('query', '')"
                        @click="$refs.searchInput.focus()"
                        x-show="$wire.query.length > 0"
                        x-cloak
                    >
                        <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" d="m6 6 8 8M14 6l-8 8" />
                        </svg>
                    </button>
                </div>

                <div class="rc-search-filters" role="group" aria-label="Suche eingrenzen">
                    @foreach(['all' => 'Alle', 'insurances' => 'Versicherungen', 'infos' => 'Informationen'] as $type => $label)
                        <button
                            type="button"
                            wire:key="search-filter-{{ $type }}"
                            wire:click="selectSearchType('{{ $type }}')"
                            aria-pressed="{{ $searchType === $type ? 'true' : 'false' }}"
                        >{{ $label }}</button>
                    @endforeach
                </div>

                <div class="rc-search-content" id="rc-search-results" data-search-results
                    wire:loading.class="is-searching" wire:target="query,selectSearchType"
                    @keydown.arrow-down.prevent="moveSearchFocus($event, 1)"
                    @keydown.arrow-up.prevent="moveSearchFocus($event, -1)"
                >
                    @php($resultCount = count($resultsInsurances) + count($resultsTypes) + count($resultsInfos))
                    @if($resultCount)
                        @foreach([
                            ['label' => 'Versicherungen', 'type' => 'insurances', 'items' => $resultsInsurances, 'field' => 'name'],
                            ['label' => 'Branchen', 'type' => 'types', 'items' => $resultsTypes, 'field' => 'name'],
                            ['label' => 'Informationen', 'type' => 'infos', 'items' => $resultsInfos, 'field' => 'title'],
                        ] as $group)
                            @if(count($group['items']))
                                <section class="rc-search-group" aria-label="{{ $group['label'] }}">
                                    <h3>{{ $group['label'] }} <span>{{ count($group['items']) }}</span></h3>
                                    <ul>
                                        @foreach($group['items'] as $result)
                                            <li wire:key="search-result-{{ $group['type'] }}-{{ $result['id'] }}">
                                                <button type="button" class="rc-search-result" data-search-result
                                                    wire:click="selectResult({{ $result['id'] }}, '{{ $group['type'] }}')"
                                                >
                                                    <span class="rc-search-result-icon" aria-hidden="true">
                                                        @if($group['type'] === 'insurances')
                                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linejoin="round" d="m12 3 8 3v6c0 4-4 7-8 9-4-2-8-5-8-9V6l8-3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="m8.5 11.5 2.5 2.5 4.5-5" /></svg>
                                                        @else
                                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linejoin="round" d="M6 3h9l4 4v14H6V3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M9 12h7m-7 4h5" /></svg>
                                                        @endif
                                                    </span>
                                                    <span class="rc-search-result-name">{{ $result[$group['field']] }}</span>
                                                    <svg class="rc-search-result-arrow" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5" /></svg>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </section>
                            @endif
                        @endforeach
                    @else
                        <div class="rc-search-empty">
                            <svg aria-hidden="true" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="14" cy="14" r="9" /><path stroke-linecap="round" d="m21 21 6 6" /></svg>
                            @if(strlen($query) >= 2)
                                <p>Keine Ergebnisse gefunden.</p>
                                <span>Versuche einen anderen Suchbegriff oder eine andere Kategorie.</span>
                            @else
                                <p>Versicherer und Informationen an einem Ort.</p>
                                <span>Gib einen Namen oder ein Stichwort ein.</span>
                            @endif
                        </div>
                    @endif
                </div>

                <footer class="rc-search-footer">
                    <p id="rc-search-hint" role="status" aria-live="polite">
                        <span wire:loading.remove wire:target="query,selectSearchType">
                            @if(strlen($query) < 2)
                                Mindestens 2 Zeichen eingeben.
                            @else
                                {{ $resultCount }} {{ $resultCount === 1 ? 'Ergebnis' : 'Ergebnisse' }} gefunden
                            @endif
                        </span>
                        <span wire:loading.delay wire:target="query,selectSearchType" class="rc-search-loading"><span aria-hidden="true"></span> Suche läuft …</span>
                    </p>
                    <span class="rc-search-key-hint"><kbd>Esc</kbd> Schließen</span>
                </footer>
            </section>
        </div>
    </template>
</div>
<div
    x-id="['app-filter-panel']"
    x-data="{
        showFilters: false,
        init() {
            this.$watch('showFilters', () => this.emitFilterPanelState());
            this.$watch(() => $store.nav.isScreenXl, () => {
                if ($store.nav.isScreenXl) this.showFilters = false;
                this.emitFilterPanelState();
            });
            this.$nextTick(() => this.emitFilterPanelState());
        },
        toggleFilters() { this.showFilters = !this.showFilters; },
        closeFilters() { this.showFilters = false; },
        emitFilterPanelState() {
            window.dispatchEvent(new CustomEvent('filter-panel-state', {
                detail: { open: this.showFilters, isDesktop: $store.nav.isScreenXl }
            }));
        }
    }"
    @keydown.escape.window="if (showFilters) { closeFilters(); $refs.filterToggle.focus(); }"
    {{ $attributes->merge(['class' => 'app-filter-layout']) }}
>
    <div class="pb-8">
        <div x-show="!$store.nav.isScreenXl" x-cloak class="mb-5 flex justify-end">
            <button type="button" x-ref="filterToggle" @click="toggleFilters()" :aria-expanded="String(showFilters)" :aria-controls="$id('app-filter-panel')"
                class="app-button-secondary inline-flex min-h-11 items-center justify-center gap-3 px-5">
                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" /><circle cx="9" cy="6" r="2" fill="var(--surface)" /><circle cx="15" cy="12" r="2" fill="var(--surface)" /><circle cx="9" cy="18" r="2" fill="var(--surface)" />
                </svg>
                Filter
            </button>
        </div>

        <div class="relative min-[1180px]:flex min-[1180px]:items-start min-[1180px]:gap-7">
            <aside
                :id="$id('app-filter-panel')"
                x-show="showFilters || $store.nav.isScreenXl"
                x-cloak
                class="app-filter-sidebar min-[1180px]:sticky min-[1180px]:top-24 min-[1180px]:w-[280px] min-[1180px]:shrink-0 max-[1179px]:fixed max-[1179px]:inset-0 max-[1179px]:z-[150] max-[1179px]:flex max-[1179px]:items-end max-[1179px]:justify-center"
                :role="$store.nav.isScreenXl ? 'complementary' : 'dialog'"
                :aria-modal="$store.nav.isScreenXl ? null : 'true'"
                aria-label="Filter"
                x-trap.inert.noscroll="showFilters && !$store.nav.isScreenXl"
            >
                <div x-show="showFilters && !$store.nav.isScreenXl" x-cloak x-transition.opacity @click="closeFilters()"
                    class="fixed inset-0 bg-[#051b23]/35 backdrop-blur-sm min-[1180px]:hidden" aria-hidden="true"></div>
                <div
                    class="app-filter-panel relative w-full rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-4 text-[var(--ink)] max-[1179px]:m-3 max-[1179px]:max-h-[calc(100dvh-24px)] max-[1179px]:max-w-md max-[1179px]:overflow-y-auto min-[1180px]:max-h-[calc(100dvh-120px)] min-[1180px]:overflow-y-auto"
                    x-ref="filterPanel"
                >
                    <div class="mb-3 flex items-center justify-between gap-4 border-b border-[var(--line)] px-2 pb-4">
                        <span class="text-sm font-semibold">Filter</span>
                        <button type="button" x-show="!$store.nav.isScreenXl" @click="closeFilters()" class="app-button-secondary min-h-11 px-4 min-[1180px]:hidden">Schließen</button>
                    </div>
                    {{ $filters }}
                </div>
            </aside>
            <div class="filter-sidebar min-w-0 flex-1">
                {{ $listContent }}
            </div>
        </div>
    </div>
</div>

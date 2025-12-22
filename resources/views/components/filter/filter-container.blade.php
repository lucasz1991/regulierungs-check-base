<div
    x-data="{
        showFilters: $persist(false),
        __openedByUser: false,

        // fixe Panelbreite (damit Fixed/Sticky nicht springt)
        panelWidth: 320, // px

        // dynamische Offsets je nach Nav-Zustand
        get offsetTop() {
            return $store.nav.showNav ? 100 : 20;
        },
        get lockThreshold() {
            return $store.nav.showNav ? 100 : 20;
        },

        isLocked: false,
        originalTop: null,

        init() {
            this.$watch('showFilters', (val) => {
                if (val && !$store.nav.isScreenXl) this.scrollToPanel();
            });

            // falls die Nav ein/ausblendet während locked -> top nachziehen
            this.$watch(() => $store.nav.showNav, () => {
                if (this.isLocked) this.applyLockedStyles();
            });

            window.addEventListener('scroll', () => {
                if (!$store.nav.isScreenXl) return;
                this.handleStickyScroll();
            }, { passive: true });
        },

        toggleFilters() {
            this.__openedByUser = true;
            this.showFilters = !this.showFilters;

            if (this.showFilters && !$store.nav.isScreenXl) this.scrollToPanel();
        },

        closeFilters() {
            this.showFilters = false;
        },

        scrollToPanel() {
            this.$nextTick(() => {
                setTimeout(() => {
                    const el = this.$refs.filterPanel;
                    if (!el) return;

                    const rect = el.getBoundingClientRect();
                    const top = window.scrollY + rect.top - this.offsetTop;

                    window.scrollTo({
                        top: Math.max(0, top),
                        behavior: 'smooth',
                    });
                }, 80);
            });
        },

        applyLockedStyles() {
            const el = this.$refs.filterPanel;
            if (!el) return;

            el.style.position = 'fixed';
            el.style.top = `${this.offsetTop}px`;

            // Breite fix (kommt hier aus Layout / panelWidth)
            el.style.width = `${this.panelWidth}px`;

            el.style.zIndex = 40;
        },

        clearLockedStyles() {
            const el = this.$refs.filterPanel;
            if (!el) return;

            el.style.position = '';
            el.style.top = '';
            el.style.width = '';
            el.style.zIndex = '';
        },

        handleStickyScroll() {
            const el = this.$refs.filterPanel;
            if (!el) return;

            const rect = el.getBoundingClientRect();

            if (this.originalTop === null) {
                this.originalTop = rect.top + window.scrollY;
            }

            // LOCK: sobald Panel oben <= threshold
            if (!this.isLocked && rect.top <= this.lockThreshold) {
                this.isLocked = true;
                this.applyLockedStyles();
            }

            // UNLOCK: wenn wieder oberhalb der ursprünglichen Position
            if (this.isLocked && (window.scrollY + this.offsetTop) < this.originalTop) {
                this.isLocked = false;
                this.clearLockedStyles();
            }
        }
    }"
    {{ $attributes->merge(['class' => '']) }}
>
    <div class="pt-2 pb-8">

        <!-- Mobile Filter Button -->
        <div x-show="!$store.nav.isScreenXl" x-cloak class="mb-4 max-xl:flex max-xl:justify-end">
            <button @click="__openedByUser = true; showFilters = !showFilters" class="text-sm text-primary hover:underline p-2 rounded-xl bg-rcgold mr-3 flex items-center justify-center shadow-xl shadow-gray-900/5 border border-rcgold-light">
                <svg :class="{ 'xl:rotate-180 max-xl:rotate-0': !showFilters, 'max-xl:rotate-180 xl:rotate-0': showFilters }"
                    xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary transform transition-all  mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class=" mr-3">Filter</span>
                <svg class="h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg"  fill="currentColor" viewBox="0 0 40.2299 36.2069"><path  d="M0,6.0345V2.0115H7.8921v4.023Zm12.0742,0V2.0115H40.2299v4.023ZM0,20.115V16.092H27.88961v4.023Zm32.6158,0V16.092h7.6141v4.023ZM0,34.1955v-4.023H18.02461v4.023Zm22.39561,0v-4.023H40.2299v4.023Z"/><circle  cx="10.0575" cy="4.023" r="4.023"/><circle  cx="30.1724" cy="18.1035" r="4.023"/><path  d="M20.115,28.161a4.023,4.023,0,1,1-4.023,4.023A4.0229,4.0229,0,0,1,20.115,28.161Z"/></svg>                           
            </button>
        </div>

        <!-- XL Layout: Flex, fixe Sidebar-Breite, Content nimmt Rest -->
        <div class="relative xl:flex xl:gap-6">

            <!-- Sidebar (fixe Breite auf XL) -->
            <div
                x-show="showFilters || $store.nav.isScreenXl"
                x-cloak
                class="z-40 md:z-10 relative
                       xl:shrink-0"
                :style="$store.nav.isScreenXl ? `width:${panelWidth}px;` : ''"
            >
                <!-- Backdrop Mobile -->
                <div
                    x-show="showFilters && !$store.nav.isScreenXl"
                    x-cloak
                    x-transition.opacity
                    class="max-xl:fixed xl:hidden inset-0"
                    @click="closeFilters()"
                >
                    <div class="absolute inset-0 bg-black/20 backdrop-blur-md"></div>
                </div>

                <!-- Filter Panel -->
                <div
                    x-show="showFilters || $store.nav.isScreenXl"
                    x-cloak
                    x-transition
                    x-ref="filterPanel"
                    class="relative flex flex-col rounded-xl bg-white p-2 text-gray-700 shadow-xl shadow-gray-900/5
                           max-xl:absolute max-xl:right-4 max-xl:top-0 max-xl:w-[20rem]"
                    :style="$store.nav.isScreenXl
                        ? `width:${panelWidth}px; max-height: calc(100vh - ${offsetTop}px - 16px);`
                        : ''"
                >
                    <div class="xl:hidden flex justify-between pb-2">
                        <span class="font-semibold">Filter</span>
                        <button class="text-xs border px-2 py-1 rounded" @click="closeFilters()">Schließen</button>
                    </div>

                    {{ $filters }}
                </div>
            </div>

            <!-- Content: nimmt Restbreite -->
            <div
                class="filter-sidebar"
                :class="(showFilters || !$store.nav.isMobile) ? 'xl:flex-1' : 'xl:w-full'"
                x-cloak
                x-transition
            >
                {{ $listContent }}
            </div>

        </div>
    </div>
</div>

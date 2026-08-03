@props([
    'buttonClass' => 'text-gray-400 hover:text-gray-600 focus:outline-none -mt-4',
    'panelClass' => 'w-64 z-50 bg-gray-100 text-sm text-gray-800 rounded shadow-lg p-3',
    'iconClass' => 'h-4 w-4',
    'iconStrokeWidth' => '2',
])

{{--
    Der Hinweis wird nach <body> teleportiert.

    @alpinejs/anchor positioniert das Panel mit `position: absolute` und bietet
    keine fixed-Strategie an. Jeder Vorfahre mit overflow:hidden - die
    Versicherungskarte, der Bewertungs-Banner, jeder .swiper - wuerde es sonst
    abschneiden. Der Teleport haengt das Panel aus diesen Containern aus,
    waehrend x-anchor es weiterhin am Info-Icon ausrichtet.

    Layout und Aussehen der umgebenden Karten bleiben davon unberuehrt.
--}}
<div
    x-data="{
        show: false,
        closeTimer: null,
        // Auf Touch-Geraeten feuert ein Tap zusaetzlich mouseenter. Ohne diese
        // Pruefung wuerde das nachfolgende click-Toggle sofort wieder schliessen.
        hoverCapable: window.matchMedia('(hover: hover)').matches,
        open() {
            clearTimeout(this.closeTimer);
            if (this.show) return;
            this.show = true;
            this.notify('open');
        },
        close() {
            clearTimeout(this.closeTimer);
            if (! this.show) return;
            this.show = false;
            this.notify('close');
        },
        // Kurze Gnadenfrist, damit der Zeiger vom Icon auf die Box wechseln kann.
        scheduleClose() {
            clearTimeout(this.closeTimer);
            this.closeTimer = setTimeout(() => this.close(), 150);
        },
        toggle() {
            this.show ? this.close() : this.open();
        },
        pointerEnter() {
            if (this.hoverCapable) this.open();
        },
        pointerLeave() {
            if (this.hoverCapable) this.scheduleClose();
        },
        // Das Panel liegt nach dem Teleport ausserhalb; deshalb vom Button aus
        // melden, damit umgebende Komponenten den offenen Zustand kennen.
        notify(state) {
            this.$refs.anchor?.dispatchEvent(
                new CustomEvent('logo-disclaimer-' + state, { bubbles: true })
            );
        },
    }"
    class="relative"
>
    <button
        x-ref="anchor"
        @mouseenter="pointerEnter()"
        @mouseleave="pointerLeave()"
        @click.prevent.stop="toggle()"
        @keydown.escape.window="close()"
        class="{{ $buttonClass }}"
        aria-label="Hinweis zur Logo-Nutzung"
        :aria-expanded="show ? 'true' : 'false'"
        type="button"
    >
        <svg class="{{ $iconClass }}" fill="none" stroke="currentColor" stroke-width="{{ $iconStrokeWidth }}" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.75 20.5 2.5 16.25 2.5 11S6.75 1.5 12 1.5 21.5 5.75 21.5 11 17.25 20.5 12 20.5z" />
        </svg>
    </button>

    <template x-teleport="body">
        <div
            x-show="show"
            x-transition
            x-anchor.offset.10="$refs.anchor"
            @mouseenter="open()"
            @mouseleave="pointerLeave()"
            @click.outside="close()"
            x-cloak
            role="tooltip"
            class="{{ $panelClass }}"
        >
            <p class="leading-snug">
                Das Logo wird ausschließlich zur Identifikation verwendet. Es besteht keine geschäftliche Verbindung. Markenrechte liegen beim jeweiligen Versicherer.
            </p>
        </div>
    </template>
</div>

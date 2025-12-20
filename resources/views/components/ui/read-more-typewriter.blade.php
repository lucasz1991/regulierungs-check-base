@props([
    'text' => '',
    'limit' => 400,
    'speed' => 12,
    'showLess' => true,
    'asHtml' => false,
    'labelMore' => 'Weiterlesen',
    'labelLess' => 'Weniger anzeigen',
    'heightAnim' => 520, // ms für das Höhen-Wachstum
])

@php
    $full = (string)($text ?? '');
@endphp

<div
    {{ $attributes->merge(['class' => '']) }}
    x-data="{
        // config
        full: @js($full),
        limit: @js((int)$limit),
        speed: @js((int)$speed),
        asHtml: @js((bool)$asHtml),
        showLess: @js((bool)$showLess),
        heightAnim: @js((int)$heightAnim),

        // state
        expanded: false,
        typing: false,

        // content parts
        shortText: '',
        remainder: '',
        typedText: '',
        typedHtml: '',
        hasEllipsis: false,

        // raf
        rafId: null,

        init() {
            const t = (this.full || '').trim();

            if (t.length <= this.limit) {
                this.shortText = t;
                this.remainder = '';
                this.hasEllipsis = false;

                this.$nextTick(() => this.setWrapHeight(this.getShortHeight()));
                return;
            }

            const cut = this.wordCut(t, this.limit);
            this.shortText = cut.short; // ohne …
            this.remainder = cut.rest;
            this.hasEllipsis = true;

            this.$nextTick(() => this.setWrapHeight(this.getShortHeight()));
        },

        wordCut(text, limit) {
            const raw = text.slice(0, limit);
            const lastSpace = raw.lastIndexOf(' ');
            const safe = lastSpace > 120 ? raw.slice(0, lastSpace) : raw;

            return {
                short: safe.trimEnd(),
                rest: text.slice(safe.length).trimStart(),
            };
        },

        get isLong() {
            const t = (this.full || '').trim();
            return t.length > this.limit;
        },

        get needsSpace() {
            if (!this.shortText || !this.remainder) return false;
            const a = this.shortText.slice(-1);
            const b = this.remainder.slice(0, 1);
            return ![' ', '\n', '\t'].includes(a) && ![',', '.', '!', '?', ':', ';'].includes(b);
        },

        // --- HEIGHT helpers ---
        setWrapHeight(h) {
            if (!this.$refs.wrap || !h) return;
            this.$refs.wrap.style.height = Math.ceil(h) + 'px';
        },

        getShortHeight() {
            if (!this.$refs.sizerShort) return null;
            return this.$refs.sizerShort.offsetHeight;
        },

        getFullHeight() {
            if (!this.$refs.sizerFull) return null;
            return this.$refs.sizerFull.offsetHeight;
        },

        animateHeightTo(target, duration = 520) {
            if (!this.$refs.wrap || !target) return;

            cancelAnimationFrame(this.rafId);

            const start = parseFloat(this.$refs.wrap.style.height) || this.getShortHeight() || 0;
            const end   = Math.ceil(target);
            const t0    = performance.now();

            const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

            const step = (now) => {
                const p = Math.min(1, (now - t0) / duration);
                const eased = easeOutCubic(p);
                const h = start + (end - start) * eased;

                this.$refs.wrap.style.height = Math.ceil(h) + 'px';

                if (p < 1) this.rafId = requestAnimationFrame(step);
            };

            this.rafId = requestAnimationFrame(step);
        },

        async toggle() {
            if (!this.expanded) {
                await this.expandWithHeight();
            } else {
                if (!this.showLess) return;
                await this.collapseWithHeight();
            }
        },

        async expandWithHeight() {
            // Ellipsis verschwindet sofort durch x-show
            this.expanded = true;

            // DOM updaten (Sizer gleiche Breite)
            await this.$nextTick();

            const fullH = this.getFullHeight();
            if (fullH) {
                // ✅ wächst smooth, kein Jump
                this.animateHeightTo(fullH, this.heightAnim);
            }

            // minimal Vorsprung geben, damit Höhe sichtbar anläuft
            await this.sleep(60);

            // tippen
            await this.typeRemainder();

            // optional: nach dem Tippen Height auf auto,
            // damit bei Resize/Fontsize-Änderung kein fester px-Wert bleibt.
            // if (this.$refs.wrap) this.$refs.wrap.style.height = 'auto';
        },

        async collapseWithHeight() {
            // tippen stoppen + reset
            this.typing = false;
            this.typedText = '';
            this.typedHtml = '';

            await this.$nextTick();

            const shortH = this.getShortHeight();
            if (shortH) this.animateHeightTo(shortH, 320);

            await this.sleep(260);
            this.expanded = false;
        },

        async typeRemainder() {
            if (!this.remainder) return;

            this.typing = true;
            this.typedText = '';
            this.typedHtml = '';

            if (this.asHtml) {
                await this.typeChars(this.remainder, true);
            } else {
                await this.typeChars(this.remainder, false);
            }

            this.typing = false;
        },

        async typeChars(str, htmlMode) {
            for (let i = 0; i < str.length; i++) {
                if (!this.expanded) break;

                const ch = str[i];
                if (htmlMode) this.typedHtml += this.escapeHtml(ch);
                else this.typedText += ch;

                await this.sleep(this.speed);
            }
        },

        sleep(ms) {
            return new Promise(r => setTimeout(r, ms));
        },

        escapeHtml(s) {
            return s
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;');
        },
    }"
>
    {{-- Height wrapper (animiert, smooth) --}}
    <div
        x-ref="wrap"
        class="overflow-hidden"
    >
        {{-- Sichtbarer Text --}}
        <div class="text-gray-700 leading-relaxed">
            <span x-text="shortText"></span>

            {{-- … verschwindet sofort beim Expand --}}
            <span x-show="hasEllipsis && !expanded" x-cloak class="select-none">…</span>

            <span x-show="expanded" x-cloak>
                <span x-show="needsSpace" x-cloak> </span>

                <span :class="typing ? 'after:content-[\"|\"] after:animate-pulse after:ml-0.5 after:text-gray-400' : ''">
                    <template x-if="!asHtml">
                        <span x-text="typedText"></span>
                    </template>
                    <template x-if="asHtml">
                        <span x-html="typedHtml"></span>
                    </template>
                </span>
            </span>
        </div>

        {{-- SIZER (unsichtbar, aber gleiche Breite => korrekte Höhenmessung) --}}
        <div class="relative h-0">
            <div class="absolute inset-x-0 top-0 -z-10 opacity-0 pointer-events-none select-none">
                <div x-ref="sizerShort" class="text-gray-700 leading-relaxed">
                    <span x-text="shortText"></span>
                </div>

                <div x-ref="sizerFull" class="text-gray-700 leading-relaxed">
                    <span x-text="shortText"></span>
                    <span> </span>
                    <span x-text="remainder"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="mt-4" x-show="isLong" x-cloak>
        <button
            type="button"
            @click="toggle()"
            class="
                inline-flex items-center gap-1.5
                rounded-full px-3 py-1.5
                text-xs font-medium
                text-primary
                bg-primary/10
                border border-primary/20
                hover:bg-primary/30
                transition-all duration-200
            "
            :class="expanded ? 'opacity-60' : 'opacity-100'"
        >
            <span x-text="expanded ? @js($labelLess) : @js($labelMore)"></span>
            <i class="fal fa-chevron-down text-[10px] transition-transform duration-200"
               :class="expanded ? 'rotate-180' : ''"></i>
        </button>
    </div>
</div>

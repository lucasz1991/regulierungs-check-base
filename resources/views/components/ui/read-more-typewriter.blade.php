@props([
    'text' => '',
    'limitPx' => 120, 
    'speed' => 12,
    'showLess' => true,
    'asHtml' => false,
    'labelMore' => 'Weiterlesen',
    'labelLess' => 'Weniger anzeigen',
    'heightAnim' => 520,    
])

@php
    $full = (string)($text ?? '');
@endphp

<div
    {{ $attributes->merge(['class' => '']) }}
    x-data="{
        // config
        full: @js($full),
        limitPx: @js((int)$limitPx),
        speed: @js((int)$speed),
        asHtml: @js((bool)$asHtml),
        showLess: @js((bool)$showLess),
        heightAnim: @js((int)$heightAnim),

        // state
        expanded: false,
        typing: false,

        // derived content
        shortText: '',
        remainder: '',
        typedText: '',
        typedHtml: '',
        hasEllipsis: false,

        // raf
        rafId: null,

        async init() {
            const t = (this.full || '').trim();

            // 1) trivial
            if (!t.length) {
                this.shortText = '';
                this.remainder = '';
                this.hasEllipsis = false;
                return;
            }

            // 2) initial render full in sizers, then compute cut by px-height
            // we do it after DOM paints
            await this.$nextTick();

            // Wenn Full-Höhe <= limitPx -> kein Weiterlesen
            const fullH = this.getFullHeightFromText(t);
            if (fullH !== null && fullH <= this.limitPx) {
                this.shortText = t;
                this.remainder = '';
                this.hasEllipsis = false;

                await this.$nextTick();
                this.setWrapHeight(this.getShortHeight());
                return;
            }

            // 3) find maximal shortText so that shortHeight <= limitPx
            const cut = this.cutTextByPxHeight(t, this.limitPx);
            this.shortText = cut.short.trimEnd();
            this.remainder = cut.rest.trimStart();
            this.hasEllipsis = true;

            await this.$nextTick();
            this.setWrapHeight(this.getShortHeight());
        },

        // --- Cutting by pixel height ---
        cutTextByPxHeight(text, limitPx) {
            // Binary search on character index
            let lo = 0;
            let hi = text.length;
            let best = 0;

            // ensure sizerShort exists
            if (!this.$refs.sizerShort) {
                return { short: text, rest: '' };
            }

            while (lo <= hi) {
                const mid = (lo + hi) >> 1;
                const candidate = text.slice(0, mid).trimEnd();

                this.$refs.sizerShortText.textContent = candidate;
                const h = this.$refs.sizerShort.offsetHeight;

                if (h <= limitPx) {
                    best = mid;
                    lo = mid + 1;
                } else {
                    hi = mid - 1;
                }
            }

            // fallback: at least a bit
            best = Math.max(0, best);

            // Prefer word boundary
            let short = text.slice(0, best);
            const lastSpace = short.lastIndexOf(' ');
            if (lastSpace > 40) short = short.slice(0, lastSpace);

            return {
                short,
                rest: text.slice(short.length),
            };
        },

        getFullHeightFromText(text) {
            if (!this.$refs.sizerFull) return null;
            this.$refs.sizerFullText.textContent = text;
            return this.$refs.sizerFull.offsetHeight;
        },

        get isLong() {
            return this.hasEllipsis && !!this.remainder;
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
            // short sizer already has shortText inside (via sizerShortText)
            return this.$refs.sizerShort.offsetHeight;
        },

        getFullHeight() {
            if (!this.$refs.sizerFull) return null;
            // full sizer uses full text (via sizerFullText)
            return this.$refs.sizerFull.offsetHeight;
        },

        animateHeightTo(target, duration = 520) {
            if (!this.$refs.wrap || !target) return;

            cancelAnimationFrame(this.rafId);

            const start = parseFloat(this.$refs.wrap.style.height) || this.getShortHeight() || 0;
            const end   = Math.ceil(target);
            const t0    = performance.now();

            const step = (now) => {
                const p = Math.min(1, (now - t0) / duration);
                const h = start + (end - start) * p; 

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
            this.expanded = true;
            await this.$nextTick();

            // set full sizer text to full (for correct full height)
            this.$refs.sizerFullText.textContent = (this.full || '').trim();

            const fullH = this.getFullHeight();
            if (fullH) this.animateHeightTo(fullH, this.heightAnim);

            await this.sleep(60);
            await this.typeRemainder();
        },

        async collapseWithHeight() {
            this.typing = false;
            this.typedText = '';
            this.typedHtml = '';

            await this.$nextTick();

            // restore short sizer text
            this.$refs.sizerShortText.textContent = this.shortText;

            const shortH = this.getShortHeight();
            if (shortH) this.animateHeightTo(shortH, 260);

            await this.sleep(220);
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

        sleep(ms) { return new Promise(r => setTimeout(r, ms)); },

        escapeHtml(s) {
            return s
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;');
        },

        // ✅ for swiper: auto collapse when element leaves viewport
        reset() {
            if (!this.expanded && !this.typing) return;

            cancelAnimationFrame(this.rafId);

            this.typing = false;
            this.typedText = '';
            this.typedHtml = '';
            this.expanded = false;

            this.$nextTick(() => {
                // restore short sizer text
                if (this.$refs.sizerShortText) this.$refs.sizerShortText.textContent = this.shortText;

                const h = this.getShortHeight();
                if (h) this.setWrapHeight(h);
            });
        },
    }"
    x-intersect:leave="reset()"
>
    {{-- Height wrapper --}}
    <div x-ref="wrap" class="overflow-hidden">
        {{-- Visible text --}}
        <div class="text-gray-700 leading-relaxed">
            <span x-text="shortText"></span>

            {{-- … disappears on expand --}}
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

        {{-- Sizers (same width, hidden) --}}
        <div class="relative h-0">
            <div class="absolute inset-x-0 top-0 -z-10 opacity-0 pointer-events-none select-none">
                <div x-ref="sizerShort" class="text-gray-700 leading-relaxed">
                    <span x-ref="sizerShortText"></span>
                </div>
                <div x-ref="sizerFull" class="text-gray-700 leading-relaxed">
                    <span x-ref="sizerFullText"></span>
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

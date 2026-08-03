<!-- Component insurance.insurance-name-startswiper  -->
<div>
    @if ($insurance->logo)
        {{--
            `disclaimerOpen` haelt das Info-Icon sichtbar, solange der Hinweis
            offen ist. Sonst wuerde der Anker beim Wechsel auf die Box
            ausgeblendet und x-anchor haette kein Bezugselement mehr.
        --}}
        <div
            x-data="{ hover: false, disclaimerOpen: false }"
            @mouseenter="hover = true"
            @mouseleave="hover = false"
            @logo-disclaimer-open="disclaimerOpen = true"
            @logo-disclaimer-close="disclaimerOpen = false"
            class="relative w-full"
        >
            <!-- Logo -->
            <img src="{{ asset('storage/' . $insurance->logo) }}"
                 class="w-full h-8 object-contain object-center rounded"
                 loading="lazy">
            <!-- Info-Icon nur bei Hover sichtbar -->
            <div
                x-show="hover || disclaimerOpen"
                x-transition.opacity.duration.150ms
                class="absolute right-0 top-0"
                x-cloak
            >
                <x-insurance.top-insurance-banner.insurance-logo-disclaim-button />
            </div>
        </div>
    @else
        <div class="h-8 w-min rounded flex items-center justify-center text-sm border px-1 font-medium shadow-sm"
             style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }};
                    color: {{ $insurance->style['font_color'] ?? '#333' }};
                    border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
            {{ strtoupper(substr($insurance->initials, 0 ,8)) }}
        </div>
    @endif
</div>

<div>
    @if ($insurance->logo)
        <div
            x-data="{ hover: false }"
            @mouseenter="hover = true"
            @mouseleave="hover = false"
            class="relative w-full"
        >
            <!-- Logo -->
            <img src="{{ asset('storage/' . $insurance->logo) }}"
                 class="w-full h-8 object-contain object-left rounded"
                 loading="lazy">

            <!-- Info-Icon nur bei Hover sichtbar -->
<div
    x-show="hover"
    x-transition.opacity.duration.150ms
    class="absolute right-0 top-0 bg-white rounded-full"
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

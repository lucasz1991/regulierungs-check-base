<div  class="">
    <div class=""
        >
        @if ($insurance->logo)
        <div class="flex items-center space-x-1 relative w-full">
            <!-- Logo -->
            <img src="{{ asset('storage/' . $insurance->logo) }}"
                class="w-[90%] h-8  object-contain object-left rounded"  loading="lazy">
            <!-- Info-Icon -->
            <x-insurance.top-insurance-banner.insurance-logo-disclaim-button />
        </div>
        @else
            <div class="h-8 w-min rounded flex items-center justify-center text-sm border px-1 font-medium shadow-sm" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
            </div>
        @endif
    </div>
</div>
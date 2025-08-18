<a href="{{ route('insurance.show-insurance', $insurance->slug) }}"   class="flex max-w-full items-stretch gap-2">
    <div class="shrink-0 py-2 transition-all duration-200 flex ">
    @if ($insurance->logo)
        <div class="sm:flex sm:items-center sm:space-x-1 relative max-sm:mb-2">
            <!-- Logo -->
            <img src="{{ asset('storage/' . $insurance->logo) }}"
                alt="Logo Versicherungs Anbieter"
                class=" h-8 mx-auto object-contain rounded">
            <!-- Info-Icon -->
            <x-insurance.insurance-logo-disclaim />
        </div>
    @else
        <div class=" w-min rounded flex items-center justify-center text-sm border px-1 font-medium shadow-sm" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
            {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
        </div>
    @endif
    </div>
    <div class="grow  min-w-0 py-2  pr-1 transition-all duration-200">
        <div>
            <h2 class="text-base break-words  truncate text-ellipsis">
                {{  $insurance->name }}
            </h2>
        </div>
    </div>
</a>
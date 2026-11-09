<div class="flex max-w-full items-center gap-2">
    <a href="{{ route('insurance.show-insurance', $insurance->slug) }}" class="min-w-0 py-1">
    @if ($insurance->logo)
        <div class="flex items-center space-x-1 relative">
            <!-- Logo -->
            <img src="{{ asset('storage/' . $insurance->logo) }}"
                alt="Logo Versicherungs Anbieter"
                class="h-9 max-w-full rounded-md bg-white p-1 object-contain" loading="lazy">
        </div>
    @else
        <div class=" w-min rounded flex items-center justify-center text-sm border px-1 font-medium shadow-sm" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
            {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
        </div>
    @endif
    </a>
    <x-insurance.insurance-logo-disclaim buttonClass="inline-flex min-h-9 min-w-9 items-center justify-center text-[var(--muted)] hover:text-[var(--accent)]" />
</div>

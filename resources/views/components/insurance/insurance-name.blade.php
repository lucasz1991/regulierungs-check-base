@props([
    'insurance',
    'size' => 'md',
    'wrapperClass' => 'flex items-stretch gap-4 mb-4',
    'disclaimerButtonClass' => 'text-gray-400 hover:text-gray-600 focus:outline-none -mt-4',
])

@php
    $sizeClasses = [
        'sm' => 'h-7',
        'md' => 'h-8',
        'lg' => 'h-10',
        'xl' => 'h-12',
    ];

    $fallbackTextClasses = [
        'sm' => 'px-2 py-1 text-sm',
        'md' => 'px-2 py-1 text-sm',
        'lg' => 'px-2.5 py-1.5 text-base',
        'xl' => 'px-3 py-2 text-lg',
    ];
@endphp

<div class="{{ $wrapperClass }}">
    <div class="flex min-w-0 max-w-full items-center transition-all duration-200">
        @if ($insurance->logo)
            <div class="flex min-w-0 max-w-full items-center gap-2">
                <img
                    src="{{ asset('storage/' . $insurance->logo) }}"
                    alt="Logo Versicherungs Anbieter"
                    class="{{ $sizeClasses[$size] ?? 'h-8' }} block min-w-0 max-w-full shrink object-contain rounded"
                    loading="lazy"
                >
                <div class="shrink-0">
                    <x-insurance.insurance-logo-disclaim :buttonClass="$disclaimerButtonClass" />
                </div>
            </div>
        @else
            <div class="w-min rounded border font-medium shadow-sm {{ $fallbackTextClasses[$size] ?? 'px-2 py-1 text-sm' }} flex items-center justify-center leading-none" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                {{ strtoupper(substr($insurance->initials, 0, 8)) }}
            </div>
        @endif
    </div>
</div>

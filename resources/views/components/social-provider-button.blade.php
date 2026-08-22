@props([
    'provider',
    'href',
    'intent' => 'continue',
])

@php
    $labels = [
        'google' => [
            'login' => 'Mit Google anmelden',
            'register' => 'Mit Google registrieren',
            'continue' => 'Mit Google fortfahren',
        ],
        'apple' => [
            'login' => 'Mit Apple anmelden',
            'register' => 'Mit Apple registrieren',
            'continue' => 'Mit Apple fortfahren',
        ],
    ];

    $label = $labels[$provider][$intent] ?? $labels[$provider]['continue'] ?? 'Fortfahren';
    $fontFamily = $provider === 'apple'
        ? '-apple-system, BlinkMacSystemFont, "SF Pro Text", "Helvetica Neue", Arial, sans-serif'
        : 'Roboto, Arial, sans-serif';
@endphp

<a
    {{ $attributes
        ->class([
            'relative inline-flex min-h-12 w-full items-center justify-center rounded-lg border px-9 py-3 text-sm font-medium leading-5 transition focus-visible:outline-none focus-visible:ring-4',
            'border-[#747775] bg-white text-[#1f1f1f] hover:border-[#5f6368] hover:bg-[#f8fafd] focus-visible:ring-[#0b57d0]/25' => $provider === 'google',
            'border-black bg-white text-black hover:shadow-sm focus-visible:ring-black/20' => $provider === 'apple',
        ])
        ->merge([
            'href' => $href,
            'aria-label' => $label,
            'style' => "font-family: {$fontFamily};",
        ]) }}
>
    <span class="absolute {{ $provider === 'apple' ? 'left-0' : 'left-4' }} top-1/2 -translate-y-1/2" aria-hidden="true">
        <x-social-provider-logo :provider="$provider" />
    </span>
    <span>{{ $label }}</span>
</a>

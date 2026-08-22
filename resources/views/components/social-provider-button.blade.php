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
            'relative inline-flex h-11 w-full items-center justify-center rounded-lg border font-medium transition focus-visible:outline-none focus-visible:ring-4',
            'gap-[10px] border-[#747775] bg-white px-3 text-sm leading-5 text-[#1f1f1f] hover:border-[#5f6368] hover:bg-[#f8fafd] focus-visible:ring-[#0b57d0]/25' => $provider === 'google',
            'border-black bg-white px-10 text-[19px] leading-[22px] text-black hover:shadow-sm focus-visible:ring-black/20' => $provider === 'apple',
        ])
        ->merge([
            'href' => $href,
            'aria-label' => $label,
            'style' => "font-family: {$fontFamily};",
        ]) }}
>
    @if ($provider === 'apple')
        <span class="absolute left-0 top-1/2 -translate-y-1/2" aria-hidden="true">
            <x-social-provider-logo :provider="$provider" />
        </span>
    @else
        <x-social-provider-logo :provider="$provider" />
    @endif
    <span>{{ $label }}</span>
</a>

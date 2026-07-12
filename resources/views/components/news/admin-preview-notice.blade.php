@props(['compact' => false])

<div
    role="status"
    {{ $attributes->class([
        'flex items-start gap-3 border border-amber-300/80 bg-amber-50 text-amber-950 shadow-sm',
        'rounded-xl px-3 py-2 text-xs' => $compact,
        'rounded-2xl px-4 py-3 text-sm sm:px-5' => ! $compact,
    ]) }}
>
    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-400/25 text-amber-900" aria-hidden="true">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>
    </span>
    <span class="min-w-0">
        <strong class="block font-bold">Entwurf · nur für Admins sichtbar</strong>
        @unless($compact)
            <span class="mt-0.5 block text-amber-900/80">Du siehst die echte User-Ansicht. Der öffentliche News-Schalter bleibt unverändert.</span>
        @endunless
    </span>
</div>

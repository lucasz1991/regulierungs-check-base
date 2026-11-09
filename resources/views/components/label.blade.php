@props(['value'])

<label {{ $attributes->merge(['class' => 'app-label block font-medium text-sm text-[var(--ink)]']) }}>
    {{ $value ?? $slot }}
</label>

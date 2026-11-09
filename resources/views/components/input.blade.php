@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'app-control border-[var(--line)] bg-[var(--surface)] text-[var(--ink)] rounded-xl focus:border-[var(--accent)] focus:ring-[var(--accent)] disabled:cursor-not-allowed disabled:opacity-60']) !!}>

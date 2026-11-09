<label class="app-filter-search block">
    <span class="mb-2 block text-xs font-medium text-[var(--muted)]">{{ $label ?? 'Suche' }}</span>
    <span class="relative block">
        <svg aria-hidden="true" class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-[var(--accent)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="10.5" cy="10.5" r="6.5" /><path stroke-linecap="round" d="m15.5 15.5 4.5 4.5" />
        </svg>
        <input type="search" autocomplete="off" {{ $attributes->merge(['class' => 'app-control block min-h-12 w-full rounded-xl border border-[var(--line)] bg-[var(--surface)] py-3 pl-11 pr-3 text-sm text-[var(--ink)] focus:border-[var(--accent)] focus:ring-[var(--accent)]']) }}>
    </span>
</label>
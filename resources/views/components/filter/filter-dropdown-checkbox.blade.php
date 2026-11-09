@props(['options'])

@php($model = $attributes->wire('model')->value())

<div
    x-data="{
        open: false,
        selected: @entangle($model).live,
        all: @js($options->pluck('id')->values()),
        search: '',
        isAllSelected() { return this.selected.length === this.all.length; },
        toggleAll() { this.selected = this.isAllSelected() ? [] : [...this.all]; },
        close() { this.open = false; }
    }"
    @keydown.escape.stop="if (open) { close(); $refs.trigger.focus(); }"
    class="app-filter-dropdown relative w-full text-left"
>
    <button type="button" x-ref="trigger" @click="open = !open" :aria-expanded="String(open)"
        class="app-control flex min-h-12 w-full items-center justify-between gap-3 rounded-xl border border-[var(--line)] bg-[var(--surface)] px-3 py-3 text-left text-sm text-[var(--ink)]">
        <span class="flex min-w-0 items-center gap-2">
            <template x-if="selected.length && selected.length < all.length">
                <span class="inline-flex items-center gap-2">
                    <span class="inline-flex min-h-6 min-w-6 items-center justify-center rounded-md bg-[var(--surface-soft)] px-1 text-xs font-semibold text-[var(--accent)]" x-text="selected.length"></span>
                    <span>ausgewählt</span>
                </span>
            </template>
            <template x-if="!selected.length || isAllSelected()"><span>Alle ausgewählt</span></template>
        </span>
        <svg aria-hidden="true" class="h-4 w-4 shrink-0 transition-transform" :class="{ 'rotate-180': open }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" /></svg>
    </button>

    <input type="hidden" x-model="selected" :value="JSON.stringify(selected)" {{ $attributes->merge(['class' => '']) }}>

    <div x-show="open" @click.outside="close()" x-transition.opacity x-cloak
        class="relative z-10 mt-2 w-full overflow-hidden rounded-xl border border-[var(--line)] bg-[var(--surface)]">
        <div class="border-b border-[var(--line)] bg-[var(--surface-soft)] px-3 py-2">
            <button type="button" @click="toggleAll()" class="min-h-10 text-left text-xs font-medium text-[var(--accent)]"
                x-text="isAllSelected() ? 'Alle entfernen' : 'Alle auswählen'"></button>
        </div>
        <template x-if="all.length > 10">
            <label class="block border-b border-[var(--line)] p-3">
                <span class="sr-only">Optionen durchsuchen</span>
                <input type="search" x-model="search" placeholder="Suche..." class="app-control min-h-11 w-full rounded-lg border border-[var(--line)] bg-[var(--surface)] px-3 py-2 text-sm text-[var(--ink)]">
            </label>
        </template>
        <div class="max-h-[35dvh] overflow-y-auto p-2">
            @foreach($options as $option)
                <label class="flex min-h-11 cursor-pointer items-start gap-3 rounded-lg px-2 py-3 text-xs leading-relaxed hover:bg-[var(--surface-soft)]"
                    x-show="!search || @js(mb_strtolower($option->name)).includes(search.toLowerCase())">
                    <input type="checkbox" :value="{{ $option->id }}" x-model="selected" class="mt-0.5 h-4 w-4 shrink-0 rounded border-[var(--line)] text-[var(--accent)] focus:ring-[var(--accent)]">
                    <span>{{ $option->name }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>
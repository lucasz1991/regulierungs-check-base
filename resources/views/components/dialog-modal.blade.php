@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg font-medium text-[var(--ink)]">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-[var(--muted)]">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end border-t border-[var(--line)] bg-[var(--surface-soft)] px-6 py-4 text-end">
        {{ $footer }}
    </div>
</x-modal>

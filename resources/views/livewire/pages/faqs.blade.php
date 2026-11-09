<div class="app-page app-faq-page" wire:loading.class="cursor-wait">
    <div class="app-shell">
        <div class="mx-auto max-w-4xl">
            <div class="app-faq-search relative mb-8 flex items-center gap-3 rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-2 pl-5 focus-within:border-[var(--accent)] focus-within:ring-4 focus-within:ring-[var(--surface-soft)]">
                <svg class="h-5 w-5 shrink-0 text-[var(--accent)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5" /><path stroke-linecap="round" d="m15.5 15.5 4.5 4.5" /></svg>
                <input type="search" wire:model.live.debounce.250ms="search" aria-label="Fragen und Antworten durchsuchen" placeholder="Frage oder Stichwort eingeben …" class="min-w-0 flex-1 border-0 bg-transparent py-3 text-base text-[var(--ink)] shadow-none placeholder:text-[var(--muted)] focus:ring-0" />
                <button type="button" wire:click="$set('search', '')" x-show="$wire.search.length > 0" x-cloak class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-[var(--muted)] hover:bg-[var(--surface-soft)] hover:text-[var(--ink)]" aria-label="Suche löschen" title="Suche löschen">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" /></svg>
                </button>
            </div>

            <div class="space-y-3">
                @forelse($faqs as $faq)
                    <section wire:key="faq-{{ $faq->id }}" x-data="{ open: false }" @click.away="open = false" class="app-faq-item overflow-hidden rounded-2xl border border-[var(--line)] bg-[var(--surface)]">
                        <h2 class="!m-0 !text-base">
                            <button type="button" x-on:click="open = !open" :aria-expanded="String(open)" aria-controls="faq-answer-{{ $faq->id }}" class="flex min-h-20 w-full items-center gap-5 px-5 py-5 text-left font-semibold text-[var(--ink)] sm:px-7">
                                <span class="flex-1">{{ $faq->key }}</span>
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--surface-soft)] text-[var(--accent)]" aria-hidden="true">
                                    <svg class="h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': open }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" /></svg>
                                </span>
                            </button>
                        </h2>
                        <div id="faq-answer-{{ $faq->id }}" x-show="open" x-cloak x-collapse>
                            <div class="app-prose border-t border-[var(--line)] px-5 py-6 text-sm leading-relaxed sm:px-7">{!! $faq->value !!}</div>
                        </div>
                    </section>
                @empty
                    <div class="app-panel py-10 text-center" role="status">
                        <p class="text-sm font-medium text-[var(--ink)]">Keine passenden Fragen gefunden.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
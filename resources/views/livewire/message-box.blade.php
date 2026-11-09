<div class="app-account app-messages min-h-[70vh]" wire:loading.class="cursor-wait">

    @section('title')
        {{ __('Nachrichten') }}
    @endsection

    <div class="app-shell app-page py-10 space-y-6">

        {{-- Header / Glass --}}
        <div class="app-panel p-6 md:p-8">
            <div class="flex justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="app-heading text-2xl md:text-3xl font-semibold text-[var(--ink)] flex items-center gap-3">
                        <i class="fal fa-inbox text-[var(--accent)]"></i>
                        <span>Nachrichten</span>
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-[var(--muted)]">
                        Du wirst hier über alle wichtigen Nachrichten informiert.
                    </p>
                </div>

                <div class="hidden md:flex items-center gap-2 text-primary">
                    <span class="inline-flex items-center gap-2 rounded-full bg-[var(--surface)] border border-[var(--line)] px-4 py-2 text-sm">
                        <i class="fal fa-bell"></i>
                        <span>Immer aktuell</span>
                    </span>
                </div>
            </div>
        </div>



        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="w-full">
                <div class="relative">
                    <i class="fal fa-search absolute left-4 top-1/2 -translate-y-1/2 text-[var(--muted)]"></i>
                    <input
                        type="text"
                        id="simple-search"
                        class="app-control w-full rounded-xl bg-[var(--surface)] border border-[var(--line)] pl-12 pr-4 py-3 text-sm
                               focus:ring-2 focus:ring-primary focus:border-primary"
                        placeholder="Suchen…"
                    >
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="rounded-xl bg-[var(--surface)] border border-[var(--line)]  overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-[var(--muted)]">
                    <thead class="text-xs uppercase bg-[var(--surface-soft)] text-[var(--ink)]">
                        <tr>
                            <th class="px-5 py-4 w-4/12">Betreff</th>
                            <th class="px-5 py-4 w-5/12">Nachricht</th>
                            <th class="px-5 py-4 w-2/12">Datum</th>
                            <th class="px-5 py-4 w-1/12 text-right"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[var(--line)]">
                        @forelse($messages as $message)
                            <tr
                                class="group cursor-pointer hover:bg-[var(--surface-soft)] transition
                                       {{ $message->status == 1 ? 'bg-[var(--surface-soft)]' : 'bg-[var(--surface)]' }}"
                                wire:click="showMessage({{ $message->id }})"
                                wire:key="{{ $message->id }}"
                            >
                                {{-- Betreff --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="shrink-0 w-9 h-9 rounded-xl
                                            {{ $message->status == 1 ? 'bg-[var(--accent)] text-[var(--surface)]' : 'bg-[var(--surface-soft)] text-[var(--muted)]' }}
                                            flex items-center justify-center">
                                            <i class="fal {{ $message->status == 1 ? 'fa-envelope-open' : 'fa-envelope' }}"></i>
                                        </span>

                                        <div class="min-w-0">
                                            <div class="font-semibold text-[var(--ink)] truncate">
                                                {{ $message->subject }}
                                            </div>
                                            @if($message->status == 1)
                                                <div class="text-xs text-blue-700/80">
                                                    Neu
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Snippet --}}
                                <td class="px-5 py-4">
                                    <span class="block truncate text-[var(--ink)]">
                                        {{ mb_substr(strip_tags($message->message), 0, 110) }}
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td class="px-5 py-4 text-[var(--muted)]">
                                    {{ $message->created_at->diffForHumans() }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-right" wire:ignore>
                                    <div x-data="{ open: false }" class="relative">
                                        <button
                                            type="button"
                                            @click.stop="open = !open"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl
                                                   text-[var(--muted)] hover:text-[var(--ink)] hover:bg-[var(--surface-soft)] transition"
                                            aria-label="Aktionen"
                                        >
                                            <i class="fal fa-ellipsis-v"></i>
                                        </button>

                                        <div
                                            x-cloak
                                            x-show="open"
                                            @click.away="open = false"
                                            x-transition.opacity
                                            class="absolute right-0 mt-2 w-44 rounded-xl bg-[var(--surface)] border border-[var(--line)] shadow-lg overflow-hidden z-20"
                                            @click.stop
                                        >
                                            <button
                                                class="w-full text-left px-4 py-2 text-sm hover:bg-[var(--surface-soft)]"
                                                @click.prevent="open=false; $wire.showMessage({{ $message->id }})"
                                            >
                                                <i class="fal fa-eye mr-2 text-[var(--muted)]"></i>
                                                Anzeigen
                                            </button>

                                            <button
                                                class="w-full text-left px-4 py-2 text-sm hover:bg-[var(--surface-soft)] text-red-600"
                                                @click.prevent="open=false; /* TODO: $wire.deleteMessage({{ $message->id }}) */"
                                            >
                                                <i class="fal fa-trash-alt mr-2 text-red-400"></i>
                                                Löschen
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-[var(--muted)]">
                                    Keine Nachrichten gefunden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Load more --}}
            @if ($messages->hasMorePages())
                <div class="p-6 border-t border-[var(--line)] text-center">
                    <button
                        wire:click="loadMore"
                        class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-medium
                               bg-[var(--surface)] border border-[var(--line)] shadow-sm
                               hover:bg-[var(--surface-soft)] transition
                               focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                        <i class="fal fa-arrow-down"></i>
                        Weitere Nachrichten laden
                    </button>
                </div>
            @endif
        </div>

        {{-- MODAL --}}
        <div
            x-data="{ show: @entangle('showMessageModal') }"
            x-cloak
            x-show="show"
            x-transition.opacity
            class="fixed inset-0 z-[150] flex items-center justify-center p-4"
            role="dialog" aria-modal="true" aria-label="Nachricht"
            x-trap.inert.noscroll="show"
            @keydown.escape.window="if (show) { show = false; $wire.set('selectedMessage', null) }"
        >
            {{-- overlay --}}
            <div class="absolute inset-0 bg-black/70" @click="show = false"></div>

            {{-- modal --}}
            <div class="relative w-full max-w-2xl rounded-3xl border border-[var(--line)] bg-[var(--surface-soft)]   overflow-hidden">
                <div class="bg-[var(--surface)] p-6 md:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="inline-flex items-center gap-2 rounded-full bg-[var(--surface-soft)] px-3 py-1 text-xs text-[var(--muted)] mb-3">
                                <i class="fal fa-clock text-[var(--muted)]"></i>
                                <span>{{ $selectedMessage ? $selectedMessage->created_at->diffForHumans() : '' }}</span>
                            </div>

                            <h3 class="text-xl font-semibold text-[var(--ink)] leading-snug">
                                {{ $selectedMessage ? $selectedMessage->subject : '' }}
                            </h3>
                        </div>

                        <button
                            type="button"
                            class="w-10 h-10 rounded-xl bg-[var(--surface-soft)] text-[var(--muted)] hover:bg-gray-200 transition flex items-center justify-center"
                            @click="show=false; $wire.set('selectedMessage', null)"
                            aria-label="Schließen"
                        >
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="mt-6 text-[var(--ink)] leading-relaxed">
                        {!! $selectedMessage ? $selectedMessage->message : '' !!}
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-medium
                                   bg-primary text-white shadow-lg shadow-primary/20 hover:opacity-95 transition"
                            @click="show=false; $wire.set('selectedMessage', null)"
                        >
                            <i class="fal fa-check"></i>
                            Schließen
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

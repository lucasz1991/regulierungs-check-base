<div class="min-h-screen bg-gradient-to-br from-[#081a2f] via-[#0b2442] to-[#0f2f55]" wire:loading.class="cursor-wait">

    @section('title')
        {{ __('Nachrichten') }}
    @endsection

    <div class="container mx-auto px-4 py-10 max-w-6xl space-y-6">

        {{-- Header / Glass --}}
        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-2xl p-6 md:p-8">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl md:text-3xl font-semibold text-white flex items-center gap-3">
                        <i class="fal fa-inbox text-blue-200/80"></i>
                        <span>Nachrichten</span>
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-blue-100/80">
                        Du wirst hier über alle wichtigen Nachrichten informiert.
                    </p>
                </div>

                <div class="hidden md:flex items-center gap-2 text-blue-100/70">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/10 px-4 py-2 text-sm">
                        <i class="fal fa-bell"></i>
                        <span>Immer aktuell</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Info Box (hell) --}}
        <div class="rounded-2xl bg-white/95 border border-white/10 shadow p-5">
            <div class="flex items-start gap-3">
                <div class="shrink-0 w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow">
                    <i class="fal fa-info-circle"></i>
                </div>
                <div class="text-sm text-gray-700 leading-relaxed">
                    <span class="text-base font-semibold text-gray-900">Hinweis:</span><br>
                    Jede neue Nachricht, die dich betrifft, wird dir direkt angezeigt, damit du immer auf dem neuesten Stand bist.
                    Schau regelmäßig in dein Postfach, um keine wichtigen Updates zu verpassen.
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="w-full md:w-1/2">
                <div class="relative">
                    <i class="fal fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="text"
                        id="simple-search"
                        class="w-full rounded-xl bg-white/95 border border-gray-200 pl-12 pr-4 py-3 text-sm
                               focus:ring-2 focus:ring-primary focus:border-primary"
                        placeholder="Suchen…"
                    >
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="rounded-3xl bg-white/95 border border-white/10 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-600">
                    <thead class="text-xs uppercase bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-5 py-4 w-4/12">Betreff</th>
                            <th class="px-5 py-4 w-5/12">Nachricht</th>
                            <th class="px-5 py-4 w-2/12">Datum</th>
                            <th class="px-5 py-4 w-1/12 text-right"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($messages as $message)
                            <tr
                                class="group cursor-pointer hover:bg-blue-50/60 transition
                                       {{ $message->status == 1 ? 'bg-blue-50' : 'bg-white' }}"
                                wire:click="showMessage({{ $message->id }})"
                                wire:key="{{ $message->id }}"
                            >
                                {{-- Betreff --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="shrink-0 w-9 h-9 rounded-xl
                                            {{ $message->status == 1 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }}
                                            flex items-center justify-center">
                                            <i class="fal {{ $message->status == 1 ? 'fa-envelope-open' : 'fa-envelope' }}"></i>
                                        </span>

                                        <div class="min-w-0">
                                            <div class="font-semibold text-gray-900 truncate">
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
                                    <span class="block truncate text-gray-700">
                                        {{ mb_substr(strip_tags($message->message), 0, 110) }}
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td class="px-5 py-4 text-gray-500">
                                    {{ $message->created_at->diffForHumans() }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-right" wire:ignore>
                                    <div x-data="{ open: false }" class="relative">
                                        <button
                                            type="button"
                                            @click.stop="open = !open"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl
                                                   text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition"
                                            aria-label="Aktionen"
                                        >
                                            <i class="fal fa-ellipsis-v"></i>
                                        </button>

                                        <div
                                            x-cloak
                                            x-show="open"
                                            @click.away="open = false"
                                            x-transition.opacity
                                            class="absolute right-0 mt-2 w-44 rounded-xl bg-white border border-gray-200 shadow-lg overflow-hidden z-20"
                                            @click.stop
                                        >
                                            <button
                                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50"
                                                @click.prevent="open=false; $wire.showMessage({{ $message->id }})"
                                            >
                                                <i class="fal fa-eye mr-2 text-gray-400"></i>
                                                Anzeigen
                                            </button>

                                            <button
                                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 text-red-600"
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
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    Keine Nachrichten gefunden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Load more --}}
            @if ($messages->hasMorePages())
                <div class="p-6 border-t border-gray-100 text-center">
                    <button
                        wire:click="loadMore"
                        class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-medium
                               bg-white border border-gray-200 shadow-sm
                               hover:bg-gray-50 transition
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
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            {{-- overlay --}}
            <div class="absolute inset-0 bg-black/70" @click="show = false"></div>

            {{-- modal --}}
            <div class="relative w-full max-w-2xl rounded-3xl border border-white/10 bg-white/10 backdrop-blur-xl shadow-2xl overflow-hidden">
                <div class="bg-white/95 p-6 md:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600 mb-3">
                                <i class="fal fa-clock text-gray-400"></i>
                                <span>{{ $selectedMessage ? $selectedMessage->created_at->diffForHumans() : '' }}</span>
                            </div>

                            <h3 class="text-xl font-semibold text-gray-900 leading-snug">
                                {{ $selectedMessage ? $selectedMessage->subject : '' }}
                            </h3>
                        </div>

                        <button
                            type="button"
                            class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 transition flex items-center justify-center"
                            @click="show=false; $wire.set('selectedMessage', null)"
                            aria-label="Schließen"
                        >
                            <i class="fal fa-times"></i>
                        </button>
                    </div>

                    <div class="mt-6 text-gray-800 leading-relaxed">
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

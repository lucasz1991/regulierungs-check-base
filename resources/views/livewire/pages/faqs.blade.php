<div class="antialiased relative" wire:loading.class="cursor-wait">
    <div class="mx-auto px-5 pb-12 container">

        <!-- Suchfeld -->
        <div class="mb-8">
            <div class="bg-white/80 rounded-xl shadow-lg px-2 md:px-6 py-4">
                <input
                    type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Suche nach einer Frage…"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2
                           text-primary
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>
        </div>

        <!-- FAQ Liste -->
        <div class="space-y-4">
            @foreach($faqs as $faq)
                <div
                    x-data="{ open: false }"
                    @click.away="open = false"
                    class="bg-white/80 rounded-xl shadow-md transition hover:shadow-lg"
                >
                    <!-- Frage -->
                    <button
                        type="button"
                        class="w-full flex items-center justify-between px-2 md:px-6 py-4
                               text-left text-base md:text-lg font-semibold text-primary"
                        x-on:click="open = !open"
                    >
                        <span>{{ $faq->key }}</span>

                        <svg
                            class="w-4 h-4 ml-4 transition-transform duration-200"
                            :class="open ? 'rotate-180' : 'rotate-0'"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Antwort -->
                    <div
                        x-show="open"
                        x-cloak
                        x-collapse
                    >
                    <div class="px-2 md:px-6 pb-4">
                        <div class="pt-2 ">
                            <p class="text-primary text-base md:text-lg leading-relaxed w-full bg-white rounded-lg px-4 py-5">
                                {!! $faq->value !!}
                            </p>
                        </div>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

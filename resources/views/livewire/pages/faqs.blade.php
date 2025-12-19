<div class="antialiased relative" wire:loading.class="cursor-wait">
    <div class="mx-auto px-5 pb-12 container">

<!-- Suchfeld -->
<div class="mb-10">
    <div
        class="
            relative
            flex items-center
            rounded-2xl
            bg-white/90
            border border-gray-200
            shadow-sm
            transition
            focus-within:ring-2
            focus-within:ring-primary/40
            focus-within:border-primary
        "
    >
        <!-- Search Icon -->
        <span class="absolute left-4 text-gray-400 pointer-events-none">
            <i class="fal fa-search"></i>
        </span>

        <!-- Input -->
        <input
            type="text"
            wire:model.live.debounce.250ms="search"
            placeholder="Frage oder Stichwort eingeben …"
            class="
                w-full
                bg-transparent
                border-0
                pl-11 pr-14 py-3
                rounded-2xl
                text-base
                text-primary
                placeholder-gray-400
                focus:outline-none
                focus:ring-0
            "
        />

        <!-- Loader -->
        <div
            wire:loading
            class="absolute right-4 text-gray-400 animate-spin"
        >
            <i class="fal fa-circle-notch"></i>
        </div>

        <!-- Clear Button -->
        <button
            type="button"
            wire:click="$set('search', '')"
            x-show="$wire.search && !$wire.__instance.loading"
            x-cloak
            class="
                absolute right-4
                text-gray-400
                hover:text-gray-600
                transition
            "
            title="Suche löschen"
        >
            <i class="fal fa-times-circle"></i>
        </button>
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
                        x-on:click="open = !open"
                        class="w-full flex items-center gap-4 px-2 md:px-6 py-4
                               text-left text-base md:text-lg font-semibold text-primary"
                    >
                        <!-- Icon links -->
                        <span
                            class="
                                flex items-center justify-center
                                w-9 h-9 rounded-full
                                border border-primary/30
                                transition-all duration-300
                            "
                            :class="open
                                ? 'bg-primary text-white scale-105 shadow-md'
                                : 'bg-white text-primary'"
                        >
                            <svg
                                class="w-4 h-4 transition-transform duration-300"
                                :class="open ? 'rotate-180' : 'rotate-0'"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="m19 9-7 7-7-7" />
                            </svg>
                        </span>

                        <!-- Titel -->
                        <span class="flex-1">
                            {{ $faq->key }}
                        </span>
                    </button>

                    <!-- Antwort -->
                    <div
                        x-show="open"
                        x-cloak
                        x-collapse
                    >
                        <div class="px-2 md:px-6 pb-4">
                            <div class="pt-2">
                                <p class="text-primary text-base md:text-lg leading-relaxed
                                          w-full bg-white rounded-lg px-4 py-5">
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

<div
    x-data="{
        showChat: @entangle('showChat').live ?? false,
        messagefront: '',
        message: @entangle('message'),
        isLoading: @entangle('isLoading'),
        chatHistory: @entangle('chatHistory'),

        sendMessage() {
            if (this.messagefront.trim() === '') return;

            this.chatHistory.push({ role: 'user', content: this.messagefront });
            this.isLoading = true;

            this.message = this.messagefront;
            Livewire.dispatch('sendMessage');

            this.messagefront = '';
        },

        lockBody() {
            // nur auf mobile/overlay sinnvoll
            this.__scrollY = window.scrollY || 0;
            document.documentElement.classList.add('overflow-hidden');
            document.body.classList.add('overflow-hidden');
            document.body.style.position = 'fixed';
            document.body.style.top = `-${this.__scrollY}px`;
            document.body.style.left = '0';
            document.body.style.right = '0';
            document.body.style.width = '100%';
        },

        unlockBody() {
            document.documentElement.classList.remove('overflow-hidden');
            document.body.classList.remove('overflow-hidden');
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.left = '';
            document.body.style.right = '';
            document.body.style.width = '';
            window.scrollTo(0, this.__scrollY || 0);
        },
    }"
    x-init="
        $watch('showChat', (v) => {
            if (v) lockBody();
            else unlockBody();
        });
    "
    class="chat-container"
>
    @if($status)
        <div>
            {{-- Floating Button --}}
            <button
                x-show="!showChat"
                x-cloak
                x-on:click="showChat = true"
                :class="{ 'bounce-in-right': !showChat }"
                class="
                    fixed bottom-20 md:bottom-4 right-4 z-30
                    rounded-full
                    focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:outline-offset-0
                "
                aria-label="Chat öffnen"
            >
                <div
                    class="
                        relative
                        bg-rcgold
                        p-2.5 md:p-3
                        rounded-full
                        shadow-2xl shadow-black/20
                        ring-1 ring-white/20
                        hover:brightness-105 transition
                    "
                >
                    <img
                        class="w-8 md:w-10"
                        alt="chaticon"
                        src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik00LjU2NjM2IDI4LjIyMjhDNi4zMDMxOCAyOC43NjIyIDguMjU2NTYgMjguMzMxMyA5LjU5NTg5IDI3LjAzNjRDOS45MjU4OSAyNi43MTczIDEwLjQxOTUgMjYuNjM3IDEwLjgzMzYgMjYuODM1MUMxMi41MTIgMjcuNjM3NiAxNC4zMTQ2IDI4LjAzMjUgMTYuMTkxNyAyOC4wMDI0QzIyLjgyMjkgMjcuOTAzNyAyOC4wMTczIDIyLjYzMjUgMjguMDE3MyAxNi4wMDE3QzI4LjAxNzMgOS41MTU3NiAyMi43NDA5IDQuMTMyODggMTYuMjU1NSA0LjAwMjM5QzkuNTcxOSAzLjg2OTUzIDQuMDE2MTQgOS4yODI2MyA0LjAwMDAyIDE1Ljk3MjNDMy45OTY1MiAxNy40MTA5IDQuMzcyMTggMTguNjU0NiA0Ljc1NzQyIDE5LjkzMDJDNC45NDU4MSAyMC41NTM5IDUuMTM2NDkgMjEuMTg1MyA1LjI4NjI1IDIxLjg1MDdDNS43NjcxNSAyMy45ODc1IDUuNTEzNyAyNi4yNDcgNC41NjYzNiAyOC4yMjI4Wk0xMS41IDE2QzExLjUgMTYuODI4NCAxMC44Mjg0IDE3LjUgMTAgMTcuNUM5LjE3MTU3IDE3LjUgOC41IDE2LjgyODQgOC41IDE2QzguNSAxNS4xNzE2IDkuMTcxNTcgMTQuNSAxMCAxNC41QzEwLjgyODQgMTQuNSAxMS41IDE1LjE3MTYgMTEuNSAxNlpNMTYgMTcuNUMxNi44Mjg0IDE3LjUgMTcuNSAxNi44Mjg0IDE3LjUgMTZDMTcuNSAxNS4xNzE2IDE2LjgyODQgMTQuNSAxNiAxNC41QzE1LjE3MTYgMTQuNSAxNC41IDE1LjE3MTYgMTQuNSAxNkMxNC41IDE2LjgyODQgMTUuMTcxNiAxNy41IDE2IDE3LjVaTTIyIDE3LjVDMjIuODI4NCAxNy41IDIzLjUgMTYuODI4NCAyMy41IDE2QzIzLjUgMTUuMTcxNiAyMi44Mjg0IDE0LjUgMjIgMTQuNUMyMS4xNzE2IDE0LjUgMjAuNSAxNS4xNzE2IDIwLjUgMTZDMjAuNSAxNi44Mjg0IDIxLjE3MTYgMTcuNSAyMiAxNy41WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg=="
                    />
                </div>
            </button>

            {{-- Overlay --}}
            <div
                x-show="showChat"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm"
                @click="showChat = false"
            ></div>

            {{-- Container --}}
            <div
                x-show="showChat"
                x-cloak
                x-transition:enter="transition ease-out duration-250 transform"
                x-transition:enter-start="translate-y-3 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition ease-in duration-180 transform"
                x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="translate-y-2 opacity-0"
                x-trap.inert="showChat"
                class="
                    fixed z-50
                    bottom-4 right-0 md:right-4
                    mx-[3vw] md:mx-0
                    w-[720px] max-w-[94vw]
                    rounded-2xl
                    shadow-2xl shadow-black/25
                    ring-1 ring-white/10
                "
                @click.away="showChat = false"
            >
                <div class="rounded-2xl bg-white/90 backdrop-blur-md border border-white/10 overflow-hidden">

                    {{-- Header --}}
                    <div class="p-3 md:p-5">
                        <div class="relative flex items-start justify-between gap-3">
                            <div class="flex items-start gap-4">
                                <div class="shrink-0">
                                    <img
                                        src="{{ asset('site-images/milan-laptop.png') }}"
                                        alt="Chatbot Avatar"
                                        class="transition-all duration-500 h-auto"
                                        :class="chatHistory.length === 0 ? 'w-36 md:w-56' : 'w-16 md:w-28'"
                                    >
                                </div>

                                <div class="min-w-0">
                                    <div class=" ">
                                        <div class="min-w-0">
                                            <h2 class="font-semibold text-base md:text-lg tracking-tight text-slate-900 mb-3 pt-2">
                                                {{ $assistantName }}
                                            </h2>
                                            <p class="text-xs md:text-sm text-slate-600">
                                                Dein Regulierungs-CHECK Assistent.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute right-0 top-0 flex items-center gap-2">
                                <button
                                    wire:click="clearChat()"
                                    class="
                                        inline-flex items-center justify-center
                                        h-10 w-10 rounded-xl
                                        bg-slate-100 text-slate-600
                                        ring-1 ring-slate-200
                                        hover:bg-slate-200 hover:text-slate-800
                                        transition
                                    "
                                    title="Chat leeren"
                                    aria-label="Chat leeren"
                                >
                                    <i class="fal fa-trash-alt"></i>
                                </button>

                                <button
                                    @click="showChat = false"
                                    class="
                                        inline-flex items-center justify-center
                                        h-10 w-10 rounded-xl
                                        bg-slate-100 text-slate-600
                                        ring-1 ring-slate-200
                                        hover:bg-slate-200 hover:text-slate-800
                                        transition
                                    "
                                    title="Schließen"
                                    aria-label="Schließen"
                                >
                                    <i class="fal fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-slate-200/70"></div>

                    {{-- Messages --}}
                    <div class="p-3 md:p-5 pt-3">
                        <div
                            class="
                                chat-messages
                                overflow-y-auto
                                rounded-2xl
                                bg-white/60
                                ring-1 ring-slate-200/70
                                px-2 md:px-3 py-2
                                min-h-10
                                max-h-[50vh]
                                overscroll-contain
                            "
                            x-ref="messages"
                            x-init="
                                $nextTick(() => setTimeout(() => {
                                    $refs.messages.scrollTo({ top: $refs.messages.scrollHeight, behavior: 'instant' })
                                }, 10));

                                $watch('chatHistory', value => {
                                    $nextTick(() => setTimeout(() => {
                                        $refs.messages.scrollTo({ top: $refs.messages.scrollHeight, behavior: 'smooth' })
                                    }, 10))
                                })
                            "
                        >
                            <template x-for="(message, index) in chatHistory" :key="index">
                                <div class="py-1.5">
                                    <div
                                        class="max-w-[85%] md:max-w-[70%] rounded-2xl px-3.5 py-3 text-sm leading-relaxed shadow-sm ring-1"
                                        :class="
                                            message.role === 'user'
                                                ? 'ml-auto bg-primary text-white ring-primary/10'
                                                : 'mr-auto bg-white text-slate-700 ring-slate-200'
                                        "
                                    >
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <strong
                                                class="text-[11px] tracking-wide uppercase"
                                                :class="message.role === 'user' ? 'text-white/70' : 'text-rcgold'"
                                                x-text="message.role === 'user' ? 'Du' : '{{ $assistantName }}'"
                                            ></strong>

                                            <span
                                                class="text-[10px]"
                                                :class="message.role === 'user' ? 'text-white/50' : 'text-slate-400'"
                                            >
                                                <i class="fal" :class="message.role === 'user' ? 'fa-user' : 'fa-sparkles'"></i>
                                            </span>
                                        </div>

                                        <span x-text="message.content" class="break-words"></span>
                                    </div>
                                </div>
                            </template>

                            {{-- Empty state inside box --}}
                            <div x-show="chatHistory.length === 0" class="p-3 md:p-4">
                                <div class="rounded-2xl bg-white/70 ring-1 ring-slate-200 p-4">
                                    <p class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                                        <i class="fal fa-lightbulb text-rcgold"></i>
                                        Vorschläge
                                    </p>
                                    <p class="mt-1 text-xs text-slate-600">
                                        Tippe auf eine Frage, um direkt zu starten.
                                    </p>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <button
                                            @click="messagefront='Wie funktioniert Regulierungs-CHECK?'; sendMessage();"
                                            class="inline-flex items-center gap-2 rounded-full bg-rcgold/15 text-slate-800 px-3 py-1.5 text-xs font-medium ring-1 ring-rcgold/20 hover:bg-rcgold/25 transition"
                                        >
                                            <i class="fal fa-question-circle text-rcgold"></i>
                                            Wie funktioniert Regulierungs-CHECK?
                                        </button>

                                        <button
                                            @click="messagefront='Sind meine Daten anonym?'; sendMessage();"
                                            class="inline-flex items-center gap-2 rounded-full bg-rcgold/15 text-slate-800 px-3 py-1.5 text-xs font-medium ring-1 ring-rcgold/20 hover:bg-rcgold/25 transition"
                                        >
                                            <i class="fal fa-user-shield text-rcgold"></i>
                                            Sind meine Daten anonym?
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Loading bubble --}}
                        <div x-show="isLoading" x-collapse class="mt-3">
                            <div class="flex items-center gap-2">
                                <div class="h-9 w-9 rounded-xl bg-rcgold/20 text-rcgold flex items-center justify-center ring-1 ring-rcgold/20">
                                    <i class="fal fa-sparkles"></i>
                                </div>

                                <div class="rounded-2xl bg-white/70 ring-1 ring-slate-200 px-4 py-2.5 text-sm text-slate-700 flex items-center gap-3">
                                    <strong class="text-xs text-rcgold">{{ $assistantName }}</strong>
                                    <span class="inline-flex items-center gap-2 text-xs text-slate-600">
                                        denkt nach
                                        <svg class="h-4 w-4 animate-spin text-rcgold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

<div class="mt-4">
    <div class="relative">

        {{-- Textarea --}}
        <textarea
            x-data="{
                resize() {
                    this.$el.style.height = 'auto';
                    this.$el.style.height = this.$el.scrollHeight + 'px';
                }
            }"
            x-init="resize()"
            @input="resize()"
            x-model="messagefront"
            @keydown.enter.prevent="sendMessage()"
            class="
                w-full
                rounded-2xl
                bg-white/70
                ring-1 ring-slate-200
                px-4 py-3
                text-sm md:text-base
                text-slate-800
                placeholder:text-slate-400
                focus:outline-none focus:ring-2 focus:ring-rcgold/40
                min-h-[3rem]
                overflow-hidden
                resize-none
                pr-28
            "
            rows="1"
            placeholder="Frage stellen…"
        ></textarea>

        {{-- Actions --}}
        <div
            class="
                flex justify-end
                absolute right-2 bottom-4
            "
        >
            <button
                @click="sendMessage()"
                class="
                    inline-flex items-center gap-2
                    rounded-xl
                    bg-rcgold
                    px-4 py-2
                    text-xs md:text-sm
                    font-semibold text-white
                    shadow-lg shadow-black/10
                    hover:brightness-105 transition
                    focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary
                "
            >
                <i class="fal fa-paper-plane"></i>
                Senden
            </button>
        </div>

    </div>
</div>


                    </div>

                </div>
            </div>
        </div>
    @endif
</div>

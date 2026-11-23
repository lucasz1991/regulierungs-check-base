<div
    x-data="{
        showChat: @entangle('showChat').live ?? false,
        messagefront: '',
        isLoading: @entangle('isLoading'),
        chatHistory: @entangle('chatHistory'),

        sendMessage() {
            const userMessage = this.messagefront.trim();

            if (userMessage === '') return;

            this.chatHistory.push({ role: 'user', content: userMessage });
            this.isLoading = true;
            this.messagefront = '';

            $wire.sendMessage(userMessage);
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
        <div class="rc-chat">
            {{-- Floating launcher --}}
            <button
                type="button"
                x-show="!showChat"
                x-cloak
                x-on:click="showChat = true"
                :class="{ 'bounce-in-right': !showChat }"
                class="rc-chat-launcher"
                aria-label="Chat mit {{ $assistantName }} öffnen"
            >
                <img src="{{ asset('site-images/milan-laptop.png') }}" alt="" aria-hidden="true">
                <span class="rc-chat-launcher-text">
                    <span>Fragen?</span>
                    <span>{{ $assistantName }} hilft</span>
                </span>
            </button>

            {{-- Overlay --}}
            <div
                x-show="showChat"
                x-cloak
                x-transition.opacity
                class="rc-chat-backdrop fixed inset-0 z-40"
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
                class="rc-chat-panel fixed z-50 bottom-4 right-0 md:right-4 mx-[3vw] md:mx-0 w-[720px] max-w-[94vw]"
                role="dialog"
                aria-modal="true"
                aria-label="Chat mit {{ $assistantName }}"
                @click.away="showChat = false"
            >
                {{-- Header --}}
                <div class="rc-chat-header p-3 md:p-5">
                    <div class="relative flex items-start justify-between gap-3">
                        <div class="flex items-start gap-4">
                            <div class="rc-chat-avatar shrink-0">
                                <img
                                    src="{{ asset('site-images/milan-laptop.png') }}"
                                    alt=""
                                    aria-hidden="true"
                                    class="transition-all duration-500 h-auto"
                                    :class="chatHistory.length === 0 ? 'w-28 md:w-44' : 'w-14 md:w-20'"
                                >
                            </div>

                            <div class="min-w-0 pt-2">
                                <span class="rc-chat-eyebrow">KI-Assistent</span>
                                <h2 class="rc-chat-title">{{ $assistantName }}</h2>
                                <p class="rc-chat-subtitle">Dein Regulierungs-CHECK Assistent.</p>
                            </div>
                        </div>

                        <div class="absolute right-0 top-0 flex items-center gap-2">
                            <button type="button" wire:click="clearChat()" class="rc-chat-icon-button" title="Chat leeren" aria-label="Chat leeren">
                                <i class="fal fa-trash-alt"></i>
                            </button>
                            <button type="button" @click="showChat = false" class="rc-chat-icon-button" title="Schließen" aria-label="Schließen">
                                <i class="fal fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="p-3 md:p-5 pt-3 md:pt-4">
                    <div
                        class="rc-chat-messages chat-messages overflow-y-auto px-2 md:px-3 py-2 min-h-10 max-h-[50vh] overscroll-contain"
                        x-ref="messages"
                        aria-live="polite"
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
                                    class="rc-chat-bubble max-w-[85%] md:max-w-[70%] px-3.5 py-3 text-sm leading-relaxed"
                                    :class="message.role === 'user' ? 'is-user ml-auto' : 'is-assistant mr-auto'"
                                >
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <strong
                                            class="rc-chat-bubble-label"
                                            x-text="message.role === 'user' ? 'Du' : '{{ $assistantName }}'"
                                        ></strong>
                                        <span class="rc-chat-bubble-icon" aria-hidden="true">
                                            <i class="fal" :class="message.role === 'user' ? 'fa-user' : 'fa-sparkles'"></i>
                                        </span>
                                    </div>

                                    <span x-text="message.content" class="break-words"></span>
                                </div>
                            </div>
                        </template>

                        {{-- Empty state inside box --}}
                        <div x-show="chatHistory.length === 0" class="p-1 md:p-2">
                            <div class="rc-chat-suggestions">
                                <p class="rc-chat-suggestions-title">
                                    <i class="fal fa-lightbulb" aria-hidden="true"></i>
                                    Vorschläge
                                </p>
                                <p class="rc-chat-suggestions-hint">Tippe auf eine Frage, um direkt zu starten.</p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button type="button" @click="messagefront='Wie funktioniert Regulierungs-CHECK?'; sendMessage();" class="rc-chat-chip">
                                        <i class="fal fa-question-circle" aria-hidden="true"></i>
                                        Wie funktioniert Regulierungs-CHECK?
                                    </button>
                                    <button type="button" @click="messagefront='Sind meine Daten anonym?'; sendMessage();" class="rc-chat-chip">
                                        <i class="fal fa-user-shield" aria-hidden="true"></i>
                                        Sind meine Daten anonym?
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Loading bubble --}}
                    <div x-show="isLoading" x-collapse class="mt-3">
                        <div class="flex items-center gap-2">
                            <div class="rc-chat-thinking-icon h-9 w-9 flex items-center justify-center" aria-hidden="true">
                                <i class="fal fa-sparkles"></i>
                            </div>
                            <div class="rc-chat-thinking px-4 py-2.5 text-sm flex items-center gap-3">
                                <strong class="rc-chat-bubble-label">{{ $assistantName }}</strong>
                                <span class="inline-flex items-center gap-2 text-xs">
                                    denkt nach
                                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Composer --}}
                    <div class="mt-4">
                        <div class="relative">
                            <label for="rc-chat-input" class="sr-only">Frage an {{ $assistantName }}</label>
                            <textarea
                                id="rc-chat-input"
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
                                class="rc-chat-input w-full px-4 py-3 text-base min-h-[3rem] overflow-hidden resize-none pr-28"
                                rows="1"
                                maxlength="1200"
                                placeholder="Frage stellen…"
                            ></textarea>

                            <div class="flex justify-end absolute right-2 bottom-3.5">
                                <button type="button" @click="sendMessage()" class="rc-chat-send">
                                    <i class="fal fa-paper-plane" aria-hidden="true"></i>
                                    Senden
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

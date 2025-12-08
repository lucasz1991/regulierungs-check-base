<div x-data="{ 
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
        }
    }" 
    class="chat-container">
    @if($status)
        <div>
            <!-- Chatbot-Button -->
            <button x-show="!showChat" x-cloak  x-on:click="showChat = !showChat"  :class="{ 'bounce-in-right': !showChat }" class="fixed bottom-20 right-4 z-20 rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:outline-offset-0 " >
                <div class="bg-rcgold  p-3 rounded-full"><img class="w-10" alt="chaticon" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik00LjU2NjM2IDI4LjIyMjhDNi4zMDMxOCAyOC43NjIyIDguMjU2NTYgMjguMzMxMyA5LjU5NTg5IDI3LjAzNjRDOS45MjU4OSAyNi43MTczIDEwLjQxOTUgMjYuNjM3IDEwLjgzMzYgMjYuODM1MUMxMi41MTIgMjcuNjM3NiAxNC4zMTQ2IDI4LjAzMjUgMTYuMTkxNyAyOC4wMDI0QzIyLjgyMjkgMjcuOTAzNyAyOC4wMTczIDIyLjYzMjUgMjguMDE3MyAxNi4wMDE3QzI4LjAxNzMgOS41MTU3NiAyMi43NDA5IDQuMTMyODggMTYuMjU1NSA0LjAwMjM5QzkuNTcxOSAzLjg2OTUzIDQuMDE2MTQgOS4yODI2MyA0LjAwMDAyIDE1Ljk3MjNDMy45OTY1MiAxNy40MTA5IDQuMzcyMTggMTguNjU0NiA0Ljc1NzQyIDE5LjkzMDJDNC45NDU4MSAyMC41NTM5IDUuMTM2NDkgMjEuMTg1MyA1LjI4NjI1IDIxLjg1MDdDNS43NjcxNSAyMy45ODc1IDUuNTEzNyAyNi4yNDcgNC41NjYzNiAyOC4yMjI4Wk0xMS41IDE2QzExLjUgMTYuODI4NCAxMC44Mjg0IDE3LjUgMTAgMTcuNUM5LjE3MTU3IDE3LjUgOC41IDE2LjgyODQgOC41IDE2QzguNSAxNS4xNzE2IDkuMTcxNTcgMTQuNSAxMCAxNC41QzEwLjgyODQgMTQuNSAxMS41IDE1LjE3MTYgMTEuNSAxNlpNMTYgMTcuNUMxNi44Mjg0IDE3LjUgMTcuNSAxNi44Mjg0IDE3LjUgMTZDMTcuNSAxNS4xNzE2IDE2LjgyODQgMTQuNSAxNiAxNC41QzE1LjE3MTYgMTQuNSAxNC41IDE1LjE3MTYgMTQuNSAxNkMxNC41IDE2LjgyODQgMTUuMTcxNiAxNy41IDE2IDE3LjVaTTIyIDE3LjVDMjIuODI4NCAxNy41IDIzLjUgMTYuODI4NCAyMy41IDE2QzIzLjUgMTUuMTcxNiAyMi44Mjg0IDE0LjUgMjIgMTQuNUMyMS4xNzE2IDE0LjUgMjAuNSAxNS4xNzE2IDIwLjUgMTZDMjAuNSAxNi44Mjg0IDIxLjE3MTYgMTcuNSAyMiAxNy41WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg=="></div>
            </button>

            <!-- Overlay -->
            <div x-show="showChat" x-cloak  x-transition:enter="transition ease-out duration-300 opacity-0" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200 opacity-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-20 z-40"></div>
            <!-- Chatbot-Container -->
            <div x-show="showChat" x-cloak  x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-full "
                x-transition:enter-end="translate-x-0 "
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-x-0 "
                x-transition:leave-end="translate-x-full"
                x-on:click.away="showChat = false"
                class="fixed bottom-4 right-0 mx-[3vw] bg-white p-3 md:p-5 transition rounded-lg border border-[#e5e7eb] w-[650px] max-w-[94vw] h-auto shadow-xl z-50" >
                <!-- Header -->
                <div class="flex justify-between pb-4">
                    <div class="flex space-x-4 items-start">
                        <div>
                            <img 
                                src="{{ asset('site-images/milan-laptop.png') }}" 
                                alt="Chatbot Avatar" 
                                class="transition ease-out duration-600 h-auto"
                                :class="chatHistory.length === 0 ? 'w-40 md:w-64' : 'w-20 md:w-36'"
                            >
                        </div>
                        <div>
                            <h2 class="font-semibold text-lg tracking-tight">{{ $assistantName }}</h2>
                            <p class="text-sm text-[#6b7280]">Dein Regulierungs-CHECK Assistent.</p>
                        </div>
                    </div>
                    <div class="flex space-x-4 items-start">
                        <button wire:click="clearChat()" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M8 7V4a1 1 0 011-1h6a1 1 0 011 1v3" />
                            </svg>
                        </button>
                        <button @click="showChat = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            <hr>
                <!-- Chat-Messages -->
                <div 
                    class="chat-messages mb-4 overflow-y-auto scroll-container bg-white rounded min-h-10 h-auto max-h-[50vh] transition-all transition py-2" 
                    x-ref="messages"
                    x-init="
                    $nextTick(() => setTimeout(() => { $refs.messages.scrollTo({ top: $refs.messages.scrollHeight, behavior: 'instant' }) }, 10));
                    $watch('chatHistory', value => {
                        $nextTick(() => setTimeout(() => { $refs.messages.scrollTo({ top: $refs.messages.scrollHeight, behavior: 'smooth' }) }, 10))
                    })"
                    x-transition >
                    <template x-for="(message, index) in chatHistory" :key="index">
                        <div class="p-2 rounded-md text-gray-600 md:w-max max-w-xs mt-2 " x-transition
                        :class="message.role === 'user' ? 'ml-auto mr-2 bg-gray-100 text-left' : 'mr-auto bg-blue-100 text-left'">
                        <strong :class="message.role != 'user' ? 'text-secondary' : 'text-primary'" x-text="message.role === 'user' ? 'Du' : '{{ $assistantName }}'"></strong>:<br> 
                        <span x-text="message.content" class="break-words"></span>
                        </div>
                    </template>
                </div>
                <!-- Ladeanimation -->
                <div x-show="isLoading" x-collapse  class=" flex gap-2 items-center text-gray-600 text-sm">
                    <div class="p-2 mb-4">
                        <div class="p-2 rounded-md bg-[#9aceff2e] text-gray-600 w-max max-w-xs mt-2 ml-2 flex gap-5 items-center" >
                            <strong>{{ $assistantName }}:</strong> 
                            <span class=""><svg class="mr-3 -ml-1 h-5 w-5 animate-spin text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                        </div>
                    </div>
                </div>
                <!-- Fragenvorschläge anzeigen, wenn Chat leer ist -->
                <div x-show="chatHistory.length === 0" class="text-sm text-gray-600 mb-5">
                    <p class="mb-2 font-semibold">Fragen, die du stellen könntest:</p>
                    <ul class="space-y-2">
                        <li>
                            <button @click="messagefront='Wie funktioniert Regulierungs-CHECK?'; sendMessage();" class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-800 hover:bg-blue-100">
                                Wie funktioniert Regulierungs-CHECK?
                            </button>
                        </li>
                        <li>
                            <button @click="messagefront='Sind meine Daten anonym?'; sendMessage();" class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-800 hover:bg-blue-100">
                                Sind meine Daten anonym?
                            </button>
                        </li>
                    </ul>
                </div>
                <!-- Eingabe & Buttons -->
                <div class="relative w-full">

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
                            @keydown.enter="sendMessage()" 
                            class="w-full border-outline bg-white border border-outline rounded-radius rounded-lg px-2 py-2  pr-24 text-md text-on-surface focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 min-h-10 overflow-hidden" 
                            rows="2" 
                            autofocus
                            placeholder="Frage stellen...">
                        </textarea>
                        <button @click="sendMessage()" class="absolute rounded-lg right-2 bottom-3 bg-secondary text-white rounded-radius px-2 py-1 text-xs tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 ">senden</button>
                    
                </div>
            </div>
        </div>
    @endif
</div>

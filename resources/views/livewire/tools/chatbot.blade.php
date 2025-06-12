<div x-data="{ 
        showChat: $persist(false).using(sessionStorage),
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
            <button x-show="!showChat" x-cloak x-on:click="showChat = !showChat"  :class="{ 'bounce-in-right': !showChat }" class="fixed bottom-4 right-4 z-50 rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:outline-offset-0 dark:focus-visible:outline-primary-dark" >
                <svg class="w-16" viewBox="0 0 838 837" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                    <path class="zoom-in-out" d="M515.718 110.605C621.291 149.011 714.28 313.637 646.577 499.743C578.874 685.848 438.407 805.582 332.834 767.176C227.261 728.77 172.327 427.631 240.03 241.525C264.94 173.052 302.98 129.691 346.376 104.145C420.926 60.2597 448.989 86.3295 515.718 110.605Z" fill="url(#linearGradient1)" fill-opacity="0.6" />
                    <path class="zoom-in-out duration-6" d="M156.136 453.009C156.169 480.494 140.036 537.977 222.056 604.127C338.957 655.053 526.064 778.991 591.853 627.973C657.641 476.956 715.723 241.089 598.822 190.163C481.921 139.237 221.924 301.992 156.136 453.009Z" fill="url(#linearGradient2)" fill-opacity="0.6" />
                    <path class="zoom-in-out duration-8" opacity="0.8" d="M766.324 448.634C743.549 558.643 594.059 674.407 400.133 634.258C206.208 594.109 67.4634 472.382 90.2388 362.373C113.014 252.364 403.08 154.565 597.005 194.714C668.356 209.486 716.75 240.874 748.289 280.13C802.469 347.57 780.72 379.1 766.324 448.634Z" fill="url(#linearGradient3)" fill-opacity="0.5" />
                    <ellipse class="animate-pulse" cx="419" cy="409" rx="419" ry="409" fill="url(#radialGradient1)" />
                    <ellipse class="animate-pulse" cx="419" cy="409" rx="419" ry="409" fill="url(#radialGradient2)" />
                    <ellipse  cx="419" cy="409" rx="419" ry="409" fill="url(#radialGradient3)" />

                    <defs>
                        <lineargradient id="linearGradient1" x1="420.705" y1="249.747" x2="423.671" y2="663.385" gradientUnits="userSpaceOnUse" >
                            <stop stop-color="#099772" />
                            <stop offset="1" stop-color="#0a93b4" stop-opacity="0" />
                        </lineargradient>

                        <lineargradient id="linearGradient2" x1="487.879" y1="-248.502" y2="140.966" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#05506a" stop-opacity="0.14" />
                            <stop offset="1" stop-color="#05506a" />
                        </lineargradient>

                        <lineargradient id="linearGradient3" x1="161.766" y1="376.102" x2="594.449" y2="567.328" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#00FFE0"/>
                            <stop offset="1" stop-color="#C626FF" stop-opacity="0" />
                        </lineargradient>

                        <radialgradient id="radialGradient1" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(419 409) rotate(90) scale(358.5 367.131)">
                            <stop stop-color="white" />
                            <stop offset="0.193741" stop-color="#E4E4E4" />
                            <stop offset="1" stop-color="#737373" stop-opacity="0" />
                        </radialgradient>

                        <radialgradient id="radialGradient2" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(419 409) rotate(90) scale(293.5 300.566)">
                            <stop stop-color="white" />
                            <stop offset="0.314072" stop-color="white" />
                            <stop offset="1" stop-color="#737373" stop-opacity="0" />
                        </radialgradient>

                        <radialgradient id="radialGradient3" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(419 409) rotate(90) scale(534 546.857)">
                            <stop stop-color="#333" stop-opacity="0" />
                            <stop offset="1" stop-color="#333" stop-opacity="0.35" />
                        </radialgradient>
                    </defs>
                </svg>
            </button>
        
            <!-- Overlay -->
            <div x-show="showChat" x-cloak x-transition:enter="transition ease-out duration-300 opacity-0" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200 opacity-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-20 z-40"></div>
            <!-- Chatbot-Container -->
            <div x-show="showChat" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-full "
                x-transition:enter-end="translate-x-0 "
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-x-0 "
                x-transition:leave-end="translate-x-full"
                x-on:click.away="showChat = false"
                class="fixed bottom-4 right-0 mx-[3vw] bg-white p-3 md:p-5 transition rounded-lg border border-[#e5e7eb] w-[440px] max-w-[94vw] h-auto shadow-xl z-50" >
                <!-- Header -->
                <div class="flex justify-between pb-4">
                    <div class="flex space-x-4 items-start">
                        <div>
                            <img 
                                src="{{ asset('site-images/assistent1.png') }}" 
                                alt="Chatbot Avatar" 
                                class="transition ease-out duration-600 h-auto"
                                :class="chatHistory.length === 0 ? 'w-40 md:w-64' : 'w-20 md:w-36'"
                            >
                        </div>
                        <div>
                            <h2 class="font-semibold text-lg tracking-tight">{{ $assistantName }}</h2>
                            <p class="text-sm text-[#6b7280]">Ich bin Milan, dein Regulierungs-Check Assistent. Mir kannst du Fragen zu Regulierungs-Check stellen.</p>
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
                        :style="'background-color: ' + (message.role === 'user' ? '#f2f2f2' : '#0c968f1c')">
                        <strong x-text="message.role === 'user' ? 'Du' : '{{ $assistantName }}'"></strong>:<br> 
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
                    <p class="mb-2 font-semibold">Fragen, die du stellen kannst:</p>
                    <ul class="space-y-2">
                        <li>
                            <button @click="messagefront='Wie funktioniert Regulierungs-Check?'; sendMessage();" class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-800 hover:bg-blue-100">
                                Wie funktioniert Regulierungs-Check?
                            </button>
                        </li>
                        <li>
                            <button @click="messagefront='Welchen nutzen hat Regulierungs-Check?'; sendMessage();" class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-800 hover:bg-blue-100">
                                Welchen nutzen hat Regulierungs-Check?
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
                            placeholder="Frage stellen...">
                        </textarea>
                        <button @click="sendMessage()" class="absolute rounded-lg right-2 bottom-3 bg-secondary text-white rounded-radius px-2 py-1 text-xs tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 ">senden</button>
                    
                </div>
            </div>
        </div>
    @endif
</div>

<div x-data="{modalIsOpen: @entangle('showFormModal')}">
    <template x-teleport="body">
        <div x-cloak x-show="modalIsOpen"  x-ref="scrollcontainer" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"  class="fixed inset-0 z-40  bg-black/20 px-4 pb-8 pt-14 backdrop-blur-md sm:items-center lg:p-8 overflow-y-auto content-center" role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
            <!-- Modal Dialog -->
            <div x-show="modalIsOpen"  x-ref="scrollTarget" @click.away="modalIsOpen = false"  x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity" x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100" class="flex flex-col gap-4 relative  mx-auto rounded-lg  shadow-xl transform transition-all container max-w-4xl border border-outline bg-gray-50  w-full  px-6 py-4 pt-8" role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
                <!-- Close (Abbrechen) Button oben rechts -->
                <button 
                    type="button"
                    @click="modalIsOpen = false"
                    class="absolute top-2 right-2 z-50 p-2 rounded-full bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="Abbrechen"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="" >
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bewertung melden</h3>
                    <x-alert class="mx-auto mb-6" role="alert">
                        <span>Du kannst eine Bewertung melden, wenn sie gegen unsere Richtlinien verstößt. Wir prüfen jede Meldung individuell.</span>
                    </x-alert>
                    <form wire:submit.prevent="submit" class="space-y-6">

                        {{-- Grund für die Meldung --}}
                        <div>
                            <label for="reportReason" class="block text-sm font-medium text-gray-700">Grund für die Meldung</label>
                            <select id="reportReason" wire:model="reportReason"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Bitte wählen</option>
                                <option value="beleidigung">Beleidigung oder Diskriminierung</option>
                                <option value="spam">Spam oder Werbung</option>
                                <option value="falschinformation">Falschangaben oder Fake</option>
                                <option value="datenschutz">Datenschutzverletzung</option>
                                <option value="sonstiges">Sonstiges</option>
                            </select>
                            @error('reportReason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Beschreibung / Kommentar --}}
                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700">Kommentar</label>
                            <textarea id="comment" wire:model="comment" rows="4"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                placeholder="Was genau ist problematisch an der Bewertung?"></textarea>
                            @error('comment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Bestätigung --}}
                        <div>
                            <label class="inline-flex items-start">
                                <input type="checkbox" wire:model="confirmed"
                                    class="border-gray-300 rounded text-blue-600 shadow-sm focus:ring-blue-500 mt-1">
                                <span class="ml-2 text-sm text-gray-700">
                                    Ich bestätige, dass meine Angaben der Wahrheit entsprechen.
                                </span>
                            </label>
                            @error('confirmed') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Bewertung melden
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </template>
</div>

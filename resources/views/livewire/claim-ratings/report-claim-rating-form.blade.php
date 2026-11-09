<div x-data="{modalIsOpen: @entangle('showFormModal')}">
    <template x-teleport="body">
        <div x-cloak x-show="modalIsOpen"  x-ref="scrollcontainer" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"  class="fixed inset-0 z-[160]  bg-black/20 px-4 pb-8 pt-14 backdrop-blur-md sm:items-center lg:p-8 overflow-y-auto content-center" role="dialog" aria-modal="true" aria-labelledby="report-claim-rating-title">
            <!-- Modal Dialog -->
            <div x-show="modalIsOpen"  x-ref="scrollTarget" @click.away="modalIsOpen = false" @keydown.escape.window="modalIsOpen = false"  x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0" class="app-panel relative mx-auto flex w-full max-w-xl transform flex-col gap-4 rounded-2xl border border-[var(--line)] bg-[var(--surface)] px-6 pb-6 pt-8 text-[var(--ink)] transition-all">
                <!-- Close (Abbrechen) Button oben rechts -->
                <button 
                    type="button"
                    @click="modalIsOpen = false"
                    class="absolute top-2 right-2 z-50 inline-flex min-h-11 min-w-11 items-center justify-center p-2 rounded-full bg-[var(--surface-soft)] hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
                    aria-label="Abbrechen"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[var(--muted)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="" >
                    <h3 id="report-claim-rating-title" class="text-lg font-semibold text-[var(--ink)] mb-4">Bewertung melden</h3>
                    <x-alert class="mx-auto mb-6" role="alert">
                        <span>Du kannst eine Bewertung melden, wenn sie gegen unsere Richtlinien verstößt. Wir prüfen jede Meldung individuell.</span>
                    </x-alert>
                    <form wire:submit.prevent="submit" class="space-y-6">
                        {{-- Grund für die Meldung --}}
                        <div>
                            <label for="reportReason" class="block text-sm font-medium text-[var(--ink)]">Grund für die Meldung</label>
                            <select id="reportReason" wire:model="reportReason"
                                class="mt-1 block w-full border border-[var(--line)] rounded-md shadow-sm focus:border-[var(--accent)] focus:ring-[var(--accent)] text-sm">
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
                            <label for="comment" class="block text-sm font-medium text-[var(--ink)]">Kommentar</label>
                            <textarea id="comment" wire:model="comment" rows="4"
                                class="mt-1 block w-full border border-[var(--line)] rounded-md shadow-sm focus:border-[var(--accent)] focus:ring-[var(--accent)] text-sm"
                                placeholder="Was genau ist problematisch an der Bewertung?"></textarea>
                            @error('comment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Bestätigung --}}
                        <div>
                            <label class="inline-flex items-start">
                                <input type="checkbox" wire:model="confirmed"
                                    class="border-[var(--line)] rounded text-[var(--accent)] shadow-sm focus:ring-[var(--accent)] mt-1">
                                <span class="ml-2 text-sm text-[var(--ink)]">
                                    Ich bestätige, dass meine Angaben der Wahrheit entsprechen.
                                </span>
                            </label>
                            @error('confirmed') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-end">
                            <button type="submit"
                                class="app-button min-h-12 px-6">
                                Bewertung melden
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </template>
</div>

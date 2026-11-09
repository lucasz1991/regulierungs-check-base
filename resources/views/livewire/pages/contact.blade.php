<div class="app-page app-contact-page" wire:loading.class="cursor-wait">
    <div class="app-shell app-stack">
        <section class="grid gap-8 lg:grid-cols-[1.2fr_.8fr] lg:items-start">
            <div class="min-w-0 py-2">
                <h2 class="app-heading">Kontakt</h2>
                <p class="app-muted mt-5 max-w-2xl text-base leading-relaxed">
                    Vielen Dank für dein Interesse an Regulierungs-CHECK! Wenn du Fragen zur Bewertung von Versicherungen hast,
                    Unterstützung bei der Nutzung unserer Analysetools benötigst oder weitere Informationen rund um Versicherungsvergleiche
                    wünschst, stehen wir dir gerne zur Verfügung.
                </p>
            </div>

            <aside class="app-panel">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--surface-soft)] text-[var(--accent)]" aria-hidden="true">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 14v-3a8 8 0 0 1 16 0v3M4 12H3v6h4v-6H4Zm16 0h1v6h-4v-6h3Zm0 6c0 2-2 3-5 3h-2" /></svg>
                    </span>
                    <h3 class="text-lg font-semibold text-[var(--ink)]">Support &amp; Kontakt</h3>
                </div>
                <div class="mt-5 grid gap-4 border-t border-[var(--line)] pt-5">
                    <div class="flex min-w-0 items-center gap-3">
                        <a href="mailto:info@regulierungs-check.de" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--surface-soft)] text-[var(--accent)]" aria-label="E-Mail">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" /><path stroke-linejoin="round" d="m3 6 9 7 9-7" /></svg>
                        </a>
                        <a href="mailto:info@regulierungs-check.de" class="min-w-0 break-words text-sm font-medium text-[var(--ink)] hover:text-[var(--accent)]">info@regulierungs-check.de</a>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="https://www.instagram.com/regulierungs_check?igsh=MWV2MWI5NTk0NWtscQ%3D%3D&utm_source=qr" target="_blank" rel="noopener noreferrer" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[var(--surface-soft)] text-[var(--accent)]" aria-label="Instagram">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" /><circle cx="12" cy="12" r="4" /><circle cx="17.5" cy="6.5" r=".75" fill="currentColor" stroke="none" /></svg>
                        </a>
                        <span class="app-muted text-sm">Folge uns auf Instagram</span>
                    </div>
                </div>
            </aside>
        </section>

        <section class="app-panel !overflow-hidden !p-0">
            <div class="grid lg:grid-cols-[1.45fr_.7fr]">
                <div class="min-w-0 p-6 sm:p-8 lg:p-10">
                    <h2 class="app-heading">Kontaktiere uns</h2>
                    <p class="app-muted mt-3 max-w-xl text-sm leading-relaxed">Egal, ob du Fragen, Vorschläge oder Wünsche hast – wir sind für dich da. Schreib uns einfach!</p>

                    @if(session()->has('success'))
                        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">{{ session('success') }}</div>
                    @endif
                    @if(session()->has('error'))
                        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">{{ session('error') }}</div>
                    @endif

                    <div class="mt-7 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-label for="name" value="Dein Name" />
                            <x-input wire:model="name" id="name" class="app-control mt-2 block w-full" type="text" name="name" autocomplete="name" required placeholder="Max Mustermann" />
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-label for="email" value="Deine E-Mail" />
                            <x-input wire:model="email" id="email" class="app-control mt-2 block w-full" type="email" name="email" autocomplete="email" required placeholder="name@beispiel.de" />
                            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <x-label for="subject" value="Betreff" />
                            <x-input wire:model="subject" id="subject" class="app-control mt-2 block w-full" type="text" name="subject" required placeholder="Worum geht es?" />
                            @error('subject') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <x-label for="message" value="Deine Nachricht" />
                            <textarea wire:model="message" id="message" name="message" rows="6" required class="app-control mt-2 block w-full" placeholder="Schreibe deine Nachricht hier..."></textarea>
                            @error('message') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex items-center justify-end pt-1 sm:col-span-2">
                            <button type="button" wire:click="send" wire:loading.attr="disabled" wire:target="send" class="app-button">
                                <span>Nachricht senden</span>
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 14 0m-6-6 6 6-6 6" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="relative min-h-56 overflow-hidden bg-[var(--surface-soft)] lg:min-h-72">
                    <img src="{{ asset('/site-images/contact_img1.jpg') }}" alt="Kontakt" class="absolute inset-0 h-full w-full object-cover" loading="lazy" />
                </div>
            </div>
        </section>
    </div>
</div>

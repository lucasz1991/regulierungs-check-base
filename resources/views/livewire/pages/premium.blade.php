<div class="app-page app-premium-page">
    <div class="app-shell app-stack">
        <section class="app-panel !p-6 sm:!p-8 lg:!p-10">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_.8fr] lg:gap-12">
                <div>
                    <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-[var(--surface-soft)] text-[var(--accent)]" aria-hidden="true">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linejoin="round" d="m12 3 9 5-9 5-9-5 9-5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="m3 12 9 5 9-5M3 16l9 5 9-5" /></svg>
                    </div>
                    <h2 class="app-heading">Abonnements – in Vorbereitung</h2>
                    <p class="app-muted mt-4 text-base leading-relaxed">
                        Regulierungs-CHECK befindet sich aktuell noch in der Aufbau- und Testphase.
                        Professionelle Abonnements folgen, sobald eine solide Datenbasis vorhanden ist.
                    </p>
                    <p class="app-muted mt-5 text-sm leading-relaxed">
                        Mit zunehmender Anzahl echter Bewertungen werden erweiterte Funktionen verfügbar:
                        <strong class="text-[var(--ink)]">vertiefte Auswertungen</strong>, <strong class="text-[var(--ink)]">professionelle Filter</strong> und
                        <strong class="text-[var(--ink)]">branchenweite Vergleichsdaten</strong>.
                    </p>
                </div>

                <aside class="rounded-2xl bg-[var(--surface-soft)] p-6 lg:p-7">
                    <h3 class="text-lg font-semibold text-[var(--ink)]">Für professionelle Anwender</h3>
                    <p class="app-muted mt-3 text-sm leading-relaxed">Versicherungsmakler, Anwälte und Gutachter erhalten später Zugriff auf spezialisierte Analyse- &amp; Sichtbarkeitsfunktionen.</p>
                    <p class="app-muted mt-5 border-t border-[var(--line)] pt-5 text-sm leading-relaxed">Wir bitten noch um etwas Geduld – jede abgegebene Bewertung hilft, diese Funktionen schneller freizuschalten.</p>
                </aside>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="/" class="app-button">Bewertung abgeben <span aria-hidden="true">↗</span></a>
                <a href="/insurances" class="app-button-secondary">Versicherungen ansehen <span aria-hidden="true">→</span></a>
            </div>
        </section>

        <section class="app-panel !p-6 sm:!p-8 lg:!p-10">
            <h2 class="app-heading">Unverbindlich voranmelden</h2>
            <p class="app-muted mt-3 max-w-2xl text-sm leading-relaxed">Lass dich benachrichtigen, sobald professionelle Abonnements verfügbar sind. Keine Verpflichtung, kein Spam.</p>

            <form wire:submit.prevent="submit" class="mt-7 grid grid-cols-1 items-end gap-5 lg:grid-cols-[1fr_1fr_auto]">
                <div>
                    <label for="premium-email" class="mb-2 block text-sm font-medium text-[var(--ink)]">E-Mail-Adresse</label>
                    <input id="premium-email" type="email" wire:model.defer="email" placeholder="name@firma.de" autocomplete="email" class="app-control w-full" />
                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="premium-role" class="mb-2 block text-sm font-medium text-[var(--ink)]">Berufsgruppe (optional)</label>
                    <select id="premium-role" wire:model.defer="role" class="app-control w-full">
                        <option value="">Bitte auswählen</option>
                        <option value="Versicherungsmakler">Versicherungsmakler</option>
                        <option value="Anwalt / Kanzlei">Anwalt / Kanzlei</option>
                        <option value="Gutachter">Gutachter</option>
                        <option value="Unternehmen">Unternehmen</option>
                        <option value="Sonstiges">Sonstiges</option>
                    </select>
                    @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit" wire:loading.attr="disabled" class="app-button">
                    <span wire:loading.remove>Voranmelden</span>
                    <span wire:loading>Senden...</span>
                    <span aria-hidden="true">→</span>
                </button>
            </form>
        </section>
    </div>
</div>
<div class="app-shell app-page app-account flex min-h-[55dvh] items-center justify-center py-12">
    <div
        class="app-panel w-full max-w-2xl px-6 py-10 text-center sm:p-10"
        x-data="{ claimHash: $persist(null) }"
        x-init="
            @if (!$claimRating->user_id)
                claimHash = '{{ $claimRating->verification_hash }}'
            @endif
        "
    >
        <span class="mx-auto mb-6 flex h-14 w-14 items-center justify-center rounded-full bg-[var(--surface-soft)] text-[var(--accent)]" aria-hidden="true">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 12 4 4 8-8" /><circle cx="12" cy="12" r="10" /></svg>
        </span>
        <h1 class="app-heading mb-4 text-[var(--ink)]">Vielen Dank für deine Bewertung</h1>
        <p class="app-muted mb-8 text-sm leading-7">
            Dein Feedback hilft anderen bei einer fairen und transparenten Entscheidung.
        </p>

        @if (!$claimRating->user_id)
            <div class="mb-8 flex gap-3 rounded-xl border border-amber-500/25 bg-amber-500/10 p-5 text-left">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-[var(--ink)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" /><path stroke-linecap="round" d="M12 7v6m0 4h.01" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-[var(--ink)]">Verifizierung erforderlich</p>
                    <p class="app-muted mt-2 text-sm leading-7">
                        Ohne Registrierung oder Login bleibt deine Bewertung gespeichert,
                        wird jedoch <span class="font-medium text-[var(--ink)]">nicht anonym veröffentlicht</span>
                        und fließt nicht in die Auswertung ein.
                    </p>
                </div>
            </div>
            <div class="flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('register') }}" class="app-button">Jetzt registrieren</a>
                <a href="{{ route('login') }}" class="app-button-secondary">Zum Login</a>
            </div>
            <p class="app-muted mt-7 text-xs leading-6">
                ✔ Deine Bewertung bleibt anonym<br>
                ✔ Keine Weitergabe personenbezogener Daten
            </p>
        @endif
    </div>
</div>
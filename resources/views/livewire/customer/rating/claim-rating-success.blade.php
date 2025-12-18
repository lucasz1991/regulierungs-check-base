<div class=" flex items-center justify-center  px-4 min-h-[50vh]">
    <div
        class="w-full max-w-2xl rounded-2xl bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl px-6 py-10 text-center"
        x-data="{ claimHash: $persist(null) }"
        x-init="
            @if (!$claimRating->user_id)
                claimHash = '{{ $claimRating->verification_hash }}'
            @endif
        "
    >

        <!-- Headline -->
        <h1 class="text-2xl font-semibold text-white mb-3">
            Vielen Dank für deine Bewertung
        </h1>

        <p class="text-sm text-blue-200 mb-8">
            Dein Feedback hilft anderen bei einer fairen und transparenten Entscheidung.
        </p>

        @if (!$claimRating->user_id)
            <!-- Hinweisbox -->
            <div class="flex gap-3 rounded-xl bg-white/10 border border-white/30 p-4 text-left mb-8">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-amber-400"
                     xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M12 5.5a7.5 7.5 0 100 15 7.5 7.5 0 000-15z" />
                </svg>

                <div>
                    <p class="text-sm font-medium text-amber-200">
                        Verifizierung erforderlich
                    </p>
                    <p class="text-sm text-white/80 mt-1">
                        Ohne Registrierung oder Login bleibt deine Bewertung gespeichert,
                        wird jedoch <span class="font-medium text-white">nicht anonym veröffentlicht</span>
                        und fließt nicht in die Auswertung ein.
                    </p>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center rounded-lg px-5 py-2.5
                          bg-blue-600 hover:bg-blue-500 text-white font-medium transition">
                    Jetzt registrieren
                </a>

                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center rounded-lg px-5 py-2.5
                          border border-white/30 text-white hover:bg-white/10 transition">
                    Zum Login
                </a>
            </div>

            <!-- Vertrauen -->
            <p class="mt-6 text-xs text-white">
                ✔ Deine Bewertung bleibt anonym<br>
                ✔ Keine Weitergabe personenbezogener Daten
            </p>
        @endif
    </div>
</div>

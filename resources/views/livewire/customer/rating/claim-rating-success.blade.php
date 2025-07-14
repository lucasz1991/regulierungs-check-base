<div class="max-w-xl mx-auto px-4 py-10 text-center" 
    x-data="{ claimHash: $persist(null) }"
    x-init="
        @if (!$claimRating->user_id)
            claimHash = '{{ $claimRating->verification_hash }}'
        @endif
    ">
    <h1 class="text-2xl font-bold text-green-700 mb-4">Vielen Dank für deine Bewertung!</h1>

    <p class="text-gray-700 mb-6">
        Deine Bewertung wurde erfolgreich gespeichert.
        @if (!$claimRating->user_id)
            Du hast sie anonym abgegeben.


            <br><br>
            <div class="flex items-start gap-3 rounded-lg bg-yellow-100 border border-yellow-300 p-4 text-yellow-800">
                <!-- Icon -->
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M12 5.5a7.5 7.5 0 100 15 7.5 7.5 0 000-15z" />
                </svg>

                <!-- Text -->
                <span class="text-sm font-semibold">
Nur mit Registrierung oder Login wird sie auch anonym veröffentlicht und in die Auswertung einbezogen.
                                <p class="text-sm font-normal text-gray-500 mt-4">
Ohne Verifizierung bleibt dein Beitrag zwar gespeichert, wird aber nicht berücksichtigt.
                                </p>
                </span>
            </div>


            <div class="mt-4 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Registrierung
                </a>
                <a href="{{ route('login') }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Login
                </a>
            </div>

        @endif
    </p>
</div>

<x-layouts.auth-layout>
    <x-slot name="title">
        Gewinn sichern
    </x-slot>

    <x-slot name="description">
        Melde dich an oder erstelle ein kostenloses Konto, damit dein Gewinn eindeutig und dauerhaft dir zugeordnet werden kann.
    </x-slot>

    <x-slot name="form">
        <div class="space-y-5">
            @if (session('promotion_error'))
                <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    {{ session('promotion_error') }}
                </div>
            @elseif ($hasPromotionToken)
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
                    <p class="font-semibold">Dein Einmal-Code wurde sicher übernommen.</p>
                    <p class="mt-1">Die Zuordnung erfolgt erst nach deiner Anmeldung oder Registrierung.</p>
                </div>
            @else
                <div role="alert" class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    Es ist kein gültiger Gewinn-Link hinterlegt. Bitte scanne den QR-Code erneut oder wende dich an das Promotion-Team.
                </div>
            @endif

            @if ($hasPromotionToken)
                <div class="grid gap-3 sm:grid-cols-2">
                    <x-buttons.button-basic mode="layoutprimary" size="lg" href="{{ route('login') }}" class="w-full justify-center">
                        Einloggen
                    </x-buttons.button-basic>

                    <x-buttons.button-basic mode="layoutsecondary" size="lg" href="{{ route('register') }}" class="w-full justify-center">
                        Konto erstellen
                    </x-buttons.button-basic>
                </div>
            @endif

            <form method="POST" action="{{ route('promotion.claim.cancel') }}">
                @csrf
                <button type="submit" class="w-full rounded-xl px-4 py-3 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                    Abbrechen und zur Startseite
                </button>
            </form>
        </div>
    </x-slot>
</x-layouts.auth-layout>

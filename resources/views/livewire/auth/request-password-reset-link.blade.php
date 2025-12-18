<x-layouts.auth-layout>
    <x-slot name="title">
        Passwort vergessen
    </x-slot>

    <x-slot name="description">
        Passwort vergessen? Kein Problem! Gib einfach deine E-Mail-Adresse ein, und wir senden dir einen Link zum Zurücksetzen deines Passworts.
        So kannst du schnell wieder auf deine Bewertungen zugreifen und weiterhin die Regulierungserfahrungen mit deiner Versicherung verfolgen oder aktualisieren.
    </x-slot>

    <x-slot name="form">
        <div class="space-y-6">

            {{-- Validation --}}
            <x-validation-errors class="mb-2" />

            {{-- Success --}}
            @if (session()->has('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 flex items-start gap-3">
                    <i class="fal fa-check-circle mt-0.5"></i>
                    <div class="text-sm">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Error --}}
            @if (session()->has('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800 flex items-start gap-3">
                    <i class="fal fa-exclamation-circle mt-0.5"></i>
                    <div class="text-sm">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" class="space-y-5">

                <div>
                    <x-label for="email" value="E-Mail" />
                    <x-input
                        id="email"
                        wire:model="email"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="name@beispiel.de"
                        class="mt-1 block w-full rounded-xl border-gray-300 p-3
                               focus:ring-2 focus:ring-primary focus:border-primary"
                    />
                    @error('email') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2 space-y-3">
                    <x-buttons.button-basic mode="layoutprimary" size="lg" class="w-full justify-center" wire:click.prevent="sendResetLink">
                        Link anfordern
                    </x-buttons.button-basic>

                    <div class="text-sm text-gray-600 text-center">
                        Doch wieder eingefallen?
                        <a href="{{ route('login') }}" wire:navigate class="text-primary underline">
                            Einloggen
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </x-slot>
</x-layouts.auth-layout>

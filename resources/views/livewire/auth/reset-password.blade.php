<x-layouts.auth-layout>
    <x-slot name="title">
        Neues Passwort
    </x-slot>

    <x-slot name="description">
        Du bist nur noch einen Schritt entfernt! Erstelle jetzt ein neues Passwort, um wieder vollen Zugriff auf dein Konto bei Regulierungs-CHECK zu erhalten.
        Schnell, sicher und unkompliziert – so kannst du direkt weitermachen und deine Bewertungen verwalten oder neue Erfahrungen teilen.
    </x-slot>

    <x-slot name="form">
        <div class="space-y-6">
            <x-validation-errors class="mb-2" />

            @if (session()->has('status'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 flex items-start gap-3">
                    <i class="fal fa-check-circle mt-0.5"></i>
                    <div class="text-sm font-medium">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="resetPassword" class="space-y-5">

                {{-- E-Mail --}}
                <div>
                    <x-label for="email" value="E-Mail-Adresse" />
                    <x-input
                        wire:model="email"
                        id="email"
                        type="email"
                        autocomplete="username"
                        placeholder="name@beispiel.de"
                        class="mt-1 block w-full rounded-xl border-gray-300 p-3
                               focus:ring-2 focus:ring-primary focus:border-primary"
                    />
                    @error('email') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>

                {{-- Neues Passwort --}}
                <div x-data="{ show: false }">
                    <x-label for="password" value="Neues Passwort" />

                    <div class="relative mt-1">
                        <input
                            :type="show ? 'text' : 'password'"
                            wire:model="password"
                            id="password"
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full rounded-xl border-gray-300 p-3 pr-12
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />

                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 px-4 flex items-center
                                   text-gray-500 hover:text-gray-800 transition"
                            title="Passwort anzeigen"
                        >
                            <i class="fal" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>

                    @error('password') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>

                {{-- Passwort bestätigen --}}
                <div x-data="{ show: false }">
                    <x-label for="password_confirmation" value="Passwort bestätigen" />

                    <div class="relative mt-1">
                        <input
                            :type="show ? 'text' : 'password'"
                            wire:model="password_confirmation"
                            id="password_confirmation"
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full rounded-xl border-gray-300 p-3 pr-12
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />

                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 px-4 flex items-center
                                   text-gray-500 hover:text-gray-800 transition"
                            title="Passwort anzeigen"
                        >
                            <i class="fal" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Action --}}
                <div class="pt-2 space-y-3">
                    <x-buttons.button-basic mode="layoutprimary" size="lg" class="w-full justify-center">
                        Passwort zurücksetzen
                    </x-buttons.button-basic>

                    <div class="text-sm text-gray-600 text-center">
                        Zurück zum
                        <a href="{{ route('login') }}" wire:navigate class="text-primary underline">
                            Login
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </x-slot>
</x-layouts.auth-layout>

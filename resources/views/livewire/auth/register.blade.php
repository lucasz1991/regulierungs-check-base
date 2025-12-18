<x-layouts.auth-layout>
    <x-slot name="title">
        Willkommen!
    </x-slot>

    <x-slot name="description">
        Noch kein Konto bei Regulierungs-CHECK? Registriere dich jetzt kostenlos und teile deine Erfahrungen mit Versicherungen,
        bewerte die Schadensregulierung und hilf anderen, die faire Wahl zu treffen. Gemeinsam schaffen wir mehr Transparenz!
    </x-slot>

    <x-slot name="form">
        <form wire:submit.prevent="register" class="space-y-6">
            @csrf

            {{-- ACCOUNT --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fal fa-user-circle text-primary"></i>
                    <span>Konto</span>
                </h3>
                <p class="text-sm text-gray-600 mt-1">E-Mail & Benutzername für deinen Login.</p>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="email" value="E-Mail" />
                        <x-input
                            id="email"
                            type="email"
                            wire:model="email"
                            autocomplete="username"
                            placeholder="name@beispiel.de"
                            class="mt-1 block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="username" value="Benutzername" />
                        <x-input
                            id="username"
                            type="text"
                            wire:model="username"
                            placeholder="z. B. MaxMustermann"
                            class="mt-1 block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <x-input-error for="username" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200"></div>

            {{-- PASSWORD --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fal fa-lock text-primary"></i>
                    <span>Sicherheit</span>
                </h3>
                <p class="text-sm text-gray-600 mt-1">Wähle ein sicheres Passwort.</p>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div x-data="{ show: false }">
                        <x-label for="password" value="Passwort" />
                        <div class="relative mt-1">
                            <input
                                :type="show ? 'text' : 'password'"
                                id="password"
                                wire:model="password"
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
                        <x-input-error for="password" class="mt-2" />
                    </div>

                    <div x-data="{ show: false }">
                        <x-label for="password_confirmation" value="Passwort bestätigen" />
                        <div class="relative mt-1">
                            <input
                                :type="show ? 'text' : 'password'"
                                id="password_confirmation"
                                wire:model="password_confirmation"
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
                        <x-input-error for="password_confirmation" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200"></div>

            {{-- PERSON --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fal fa-id-card text-primary"></i>
                    <span>Persönliche Daten</span>
                </h3>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-label for="first_name" value="Vorname" />
                        <x-input
                            id="first_name"
                            type="text"
                            wire:model="first_name"
                            placeholder="Vorname"
                            class="mt-1 block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <x-input-error for="first_name" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="last_name" value="Nachname" />
                        <x-input
                            id="last_name"
                            type="text"
                            wire:model="last_name"
                            placeholder="Nachname"
                            class="mt-1 block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <x-input-error for="last_name" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200"></div>

            {{-- CONTACT --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fal fa-address-book text-primary"></i>
                    <span>Kontakt</span>
                </h3>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-label for="phone_number" value="Telefonnummer" />
                        <x-input
                            id="phone_number"
                            type="tel"
                            wire:model="phone_number"
                            placeholder="Telefonnummer"
                            class="mt-1 block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <x-input-error for="phone_number" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="street" value="Straße" />
                        <x-input
                            id="street"
                            type="text"
                            wire:model="street"
                            placeholder="Straße"
                            class="mt-1 block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <x-input-error for="street" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="city" value="Stadt" />
                        <x-input
                            id="city"
                            type="text"
                            wire:model="city"
                            placeholder="Stadt"
                            class="mt-1 block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <x-input-error for="city" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="postal_code" value="Postleitzahl" />
                        <x-input
                            id="postal_code"
                            type="text"
                            wire:model="postal_code"
                            placeholder="PLZ"
                            class="mt-1 block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <x-input-error for="postal_code" class="mt-2" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-label for="country" value="Land" />
                        <x-input
                            id="country"
                            type="text"
                            wire:model="country"
                            placeholder="Land"
                            class="mt-1 block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />
                        <x-input-error for="country" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200"></div>

{{-- TERMS --}}
<div class="space-y-3">
    <label for="terms" class="flex items-start gap-4 cursor-pointer select-none">
        <!-- Toggle (alter Style) -->
        <div class="pt-1">
            <input
                id="terms"
                type="checkbox"
                wire:model="terms"
                class="sr-only peer"
            />

            <div
                class="relative w-9 h-5 bg-gray-200 rounded-full
                       peer-focus:outline-none
                       peer-focus:ring-4 peer-focus:ring-primary/30
                       peer-checked:bg-primary
                       after:content-['']
                       after:absolute after:top-[2px] after:left-[2px]
                       after:h-4 after:w-4 after:bg-white
                       after:border after:border-gray-300
                       after:rounded-full after:transition-all
                       peer-checked:after:translate-x-full"
            ></div>
        </div>

        <!-- Text -->
        <span class="text-sm text-gray-700 leading-relaxed">
            Mit der Erstellung eines Kontos stimme ich den
            <a href="/termsandconditions" wire:navigate class="text-primary underline">
                Allgemeinen Geschäftsbedingungen
            </a>
            und der
            <a href="/privacypolicy" wire:navigate class="text-primary underline">
                Datenschutzerklärung
            </a>
            zu.
        </span>
    </label>

    <x-input-error for="terms" class="mt-2" />
</div>


            {{-- ACTIONS --}}
            <div class="pt-2 space-y-3">
                <x-buttons.button-basic mode="layoutprimary" size="lg" class="w-full justify-center">
                    Registrieren
                </x-buttons.button-basic>

                <div class="text-sm text-gray-600 text-center">
                    Du hast schon ein Konto?
                    <a href="/login" wire:navigate class="text-primary underline">Einloggen</a>.
                </div>
            </div>
        </form>
    </x-slot>
</x-layouts.auth-layout>

<x-layouts.auth-layout>
    <x-slot name="title">
    Willkommen zurück!
    </x-slot>
    <x-slot name="description">
    Willkommen zurück bei Regulierungs-CHECK! Melde dich jetzt an, um auf deine Bewertungen zuzugreifen, neue Erfahrungen mit Versicherungen zu teilen oder den aktuellen Stand deiner Einreichungen zu verfolgen. Transparent, unabhängig und einfach.
    </x-slot>
    <x-slot name="form">
      <div class="mt-2">

    <form wire:submit.prevent="login" class="space-y-5">
        @csrf

        {{-- E-Mail --}}
        <div>
            <x-label for="email" value="E-Mail" />
            <x-input
                id="email"
                type="email"
                wire:model="email"
                required
                autofocus
                autocomplete="username"
                placeholder="name@beispiel.de"
                class="mt-1 block w-full rounded-xl border-gray-300 p-3
                       focus:ring-2 focus:ring-primary focus:border-primary"
            />
            <x-input-error for="email" class="mt-2" />
        </div>

        {{-- Passwort --}}
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-gray-700">
                Passwort
            </label>

            <div class="relative mt-1">
                <input
                    :type="show ? 'text' : 'password'"
                    id="password"
                    wire:model="password"
                    required
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

{{-- Remember --}}
<div class="flex items-center justify-between gap-4">

    <label for="remember_me" class="flex items-center gap-3 cursor-pointer select-none">
        <!-- Toggle -->
        <div>
            <input
                id="remember_me"
                type="checkbox"
                wire:model="remember"
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

        <span class="text-sm text-gray-700">
            Angemeldet bleiben
        </span>
    </label>

    @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}"
           class="text-sm text-primary hover:text-blue-500 transition">
            Passwort vergessen?
        </a>
    @endif

</div>

        {{-- Actions --}}
        <div class="pt-4 space-y-3">
            <x-buttons.button-basic
                mode="layoutprimary"
                size="lg"
                class="w-full justify-center"
            >
                Einloggen
            </x-buttons.button-basic>

            <x-buttons.button-basic
                mode="layoutsecondary"
                href="{{ route('register') }}"
                class="w-full justify-center"
            >
                Registrieren
            </x-buttons.button-basic>
        </div>

    </form>

</div>

    </x-slot>
</x-layouts.auth-layout>




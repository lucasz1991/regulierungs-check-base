<x-layouts.auth-layout>
    <x-slot name="title">
    Willkommen zurück!
    </x-slot>
    <x-slot name="description">
    Willkommen zurück bei Regulierungs-CHECK! Melde dich jetzt an, um auf deine Bewertungen zuzugreifen, neue Erfahrungen mit Versicherungen zu teilen oder den aktuellen Stand deiner Einreichungen zu verfolgen. Transparent, unabhängig und einfach.
    </x-slot>
    <x-slot name="form">
      <div  class="mt-8 ">

        <form wire:submit.prevent="login">
        @csrf

        <div>
        <x-label for="email" value="E-Mail" />
        <x-input 
            id="email" 
            class="block mt-1 w-full" 
            type="email" 
            wire:model="email" 
            required 
            autofocus 
            autocomplete="username" 
        />
        <x-input-error for="email" class="mt-2" />
        </div>

        <div class="mt-4">
                    <div class="" x-data="{ show: false }">
                        <div class="relative" @click.away="show = false">
                            <label for="password" class="block text-sm font-medium text-gray-700">Passwort</label>
                            <input 
                                type="password" 
                                :type="show ? 'text' : 'password'" 
                                id="password" 
                                name="password" 
                                wire:model="password"
                                class="w-full rounded-lg border-gray-300 p-3 mt-1 text-base"
                                placeholder="Passwort" 
                                
                            />
                            <button @click="show = !show" type="button" class="absolute inset-y-0 right-1 pr-3 flex items-center text-sm text-gray-600 hover:text-gray-900 opacity-50 hover:opacity-100 " title="Passwort anzeigen">
                                <span class="sr-only">Passwort anzeigen</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        <x-input-error for="password" class="mt-2" />
                    </div>
        </div>

        <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center mb-5 cursor-pointer">
            <input 
                id="remember_me" 
                name="remember" 
                type="checkbox" 
                wire:model="remember" 
                class="sr-only peer" 
            />
            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Angemeldet bleiben</span>
        </label>
        </div>

        <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                Hast du dein Passwort vergessen?
            </a>
        @endif
        <div class="flex flex-wrap justify-end gap-3 ml-3 max-md:flex-col max-md:items-end flex-row-reverse">
          <x-buttons.button-basic mode="layoutprimary" size="md">
              Einloggen
          </x-buttons.button-basic>
          <x-buttons.button-basic mode="layoutsecondary" class="ms-0 md:ms-4" href="{{ route('register') }}">
              Registrieren
          </x-buttons.button-basic>
        </div>
        </div>
        </form>

        </div>
    </x-slot>
</x-layouts.auth-layout>




<x-form-section submit="updatePassword">
    <x-slot name="title">
        <div class="flex items-center gap-2">
            <i class="fal fa-lock text-primary"></i>
            <span>{{ __('Passwort aktualisieren') }}</span>
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="flex items-start gap-2">
            <span>{{ __('Stelle sicher, dass dein Konto ein langes, zufälliges Passwort verwendet, um sicher zu bleiben.') }}</span>
        </div>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Aktuelles Passwort --}}
            <div x-data="{ show: false }">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                    <i class="fal fa-key text-gray-400"></i>
                    {{ __('Aktuelles Passwort') }}
                </label>

                <div class="relative">
                    <input
                        :type="show ? 'text' : 'password'"
                        id="current_password"
                        wire:model="state.current_password"
                        autocomplete="current-password"
                        class="block w-full rounded-xl border-gray-300 p-3 pr-12
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

                <x-input-error for="current_password" class="mt-2" />
            </div>

            {{-- Neues Passwort --}}
            <div x-data="{ show: false }">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                    <i class="fal fa-lock text-gray-400"></i>
                    {{ __('Neues Passwort') }}
                </label>

                <div class="relative">
                    <input
                        :type="show ? 'text' : 'password'"
                        id="password"
                        wire:model="state.password"
                        autocomplete="new-password"
                        class="block w-full rounded-xl border-gray-300 p-3 pr-12
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

            {{-- Passwort bestätigen --}}
            <div x-data="{ show: false }">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                    <i class="fal fa-lock-alt text-gray-400"></i>
                    {{ __('Passwort bestätigen') }}
                </label>

                <div class="relative">
                    <input
                        :type="show ? 'text' : 'password'"
                        id="password_confirmation"
                        wire:model="state.password_confirmation"
                        autocomplete="new-password"
                        class="block w-full rounded-xl border-gray-300 p-3 pr-12
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
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Gespeichert.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled">
            <i class="fal fa-save mr-2"></i>
            {{ __('Speichern') }}
        </x-button>
    </x-slot>
</x-form-section>

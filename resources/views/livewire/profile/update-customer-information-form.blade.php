<x-form-section submit="save">
    <x-slot name="title">
        <div class="flex items-center gap-2">
            <i class="fal fa-address-card text-primary"></i>
            <span>{{ __('Kundeninformationen') }}</span>
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="flex items-start gap-2">
            <span>{{ __('Aktualisiere alle persönlichen und adressbezogenen Details deines Profils.') }}</span>
        </div>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Vorname --}}
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fal fa-user text-gray-400"></i>
                    <x-label for="first_name" value="{{ __('Vorname') }}" />
                </div>

                <x-input
                    id="first_name"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 p-3
                           focus:ring-2 focus:ring-primary focus:border-primary"
                    wire:model="first_name"
                    required
                    autocomplete="given-name"
                />

                <x-input-error for="first_name" class="mt-2" />
            </div>

            {{-- Nachname --}}
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fal fa-user text-gray-400"></i>
                    <x-label for="last_name" value="{{ __('Nachname') }}" />
                </div>

                <x-input
                    id="last_name"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 p-3
                           focus:ring-2 focus:ring-primary focus:border-primary"
                    wire:model="last_name"
                    required
                    autocomplete="family-name"
                />

                <x-input-error for="last_name" class="mt-2" />
            </div>

            {{-- Telefonnummer --}}
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fal fa-phone text-gray-400"></i>
                    <x-label for="phone_number" value="{{ __('Telefonnummer') }}" />
                </div>

                <x-input
                    id="phone_number"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 p-3
                           focus:ring-2 focus:ring-primary focus:border-primary"
                    wire:model="phone_number"
                    autocomplete="tel"
                />

                <x-input-error for="phone_number" class="mt-2" />
            </div>

            {{-- Straße --}}
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fal fa-road text-gray-400"></i>
                    <x-label for="street" value="{{ __('Straße') }}" />
                </div>

                <x-input
                    id="street"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 p-3
                           focus:ring-2 focus:ring-primary focus:border-primary"
                    wire:model="street"
                    autocomplete="street-address"
                />

                <x-input-error for="street" class="mt-2" />
            </div>

            {{-- Stadt --}}
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fal fa-city text-gray-400"></i>
                    <x-label for="city" value="{{ __('Stadt') }}" />
                </div>

                <x-input
                    id="city"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 p-3
                           focus:ring-2 focus:ring-primary focus:border-primary"
                    wire:model="city"
                />

                <x-input-error for="city" class="mt-2" />
            </div>

            {{-- Bundesland --}}
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fal fa-map text-gray-400"></i>
                    <x-label for="state" value="{{ __('Bundesland') }}" />
                </div>

                <x-input
                    id="state"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 p-3
                           focus:ring-2 focus:ring-primary focus:border-primary"
                    wire:model="state"
                />

                <x-input-error for="state" class="mt-2" />
            </div>

            {{-- Postleitzahl --}}
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fal fa-mailbox text-gray-400"></i>
                    <x-label for="postal_code" value="{{ __('Postleitzahl') }}" />
                </div>

                <x-input
                    id="postal_code"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 p-3
                           focus:ring-2 focus:ring-primary focus:border-primary"
                    wire:model="postal_code"
                />

                <x-input-error for="postal_code" class="mt-2" />
            </div>

            {{-- Land --}}
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <i class="fal fa-globe-europe text-gray-400"></i>
                    <x-label for="country" value="{{ __('Land') }}" />
                </div>

                <x-input
                    id="country"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 p-3
                           focus:ring-2 focus:ring-primary focus:border-primary"
                    wire:model="country"
                />

                <x-input-error for="country" class="mt-2" />
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

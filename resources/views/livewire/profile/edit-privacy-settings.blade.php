<x-form-section submit="save">
    <x-slot name="title">
        {{ __('Privatsphäre-Einstellungen') }}
    </x-slot>
    <x-slot name="description">
        {{ __('Aktualisiere alle Privatsphäre-Einstellungen deines Profils.') }}
    </x-slot>
    <x-slot name="form">
        <div class="col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
            {{-- Kommentare --}}
            <div class="space-y-2">
                <h2 class="font-semibold">Kommentare</h2>
                <label class="block">Name anzeigen für:</label>
                <select wire:model.defer="privacy.comments.name_visibility" class="w-full border p-2 rounded">
                    <option value="all">Alle</option>
                    <option value="users">Nur eingeloggte Benutzer</option>
                    <option value="none">Niemand</option>
                </select>
                <label class="block mt-2">Profilbild anzeigen für:</label>
                <select wire:model.defer="privacy.comments.avatar_visibility" class="w-full border p-2 rounded">
                    <option value="all">Alle</option>
                    <option value="users">Nur eingeloggte Benutzer</option>
                    <option value="none">Niemand</option>
                </select>
            </div>
            {{-- Bewertungen --}}
            <div class="space-y-2">
                <h2 class="font-semibold">Bewertungen</h2>
                <label class="block">Name anzeigen für:</label>
                <select wire:model.defer="privacy.ratings.name_visibility" class="w-full border p-2 rounded">
                    <option value="all">Alle</option>
                    <option value="users">Nur eingeloggte Benutzer</option>
                    <option value="none">Niemand</option>
                </select>
                <label class="block mt-2">Profilbild anzeigen für:</label>
                <select wire:model.defer="privacy.ratings.avatar_visibility" class="w-full border p-2 rounded">
                    <option value="all">Alle</option>
                    <option value="users">Nur eingeloggte Benutzer</option>
                    <option value="none">Niemand</option>
                </select>
            </div>
        </div>
    </x-slot>
    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Gespeichert.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled">
            {{ __('Speichern') }}
        </x-button>
    </x-slot>
</x-form-section>
<x-form-section submit="save">
    <x-slot name="title">
        <div class="flex items-center gap-2">
            <i class="fal fa-user-shield text-primary"></i>
            <span>{{ __('Privatsphäre-Einstellungen') }}</span>
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="flex items-start gap-2">
            <span>{{ __('Lege fest, wer deinen Namen und dein Profilbild sehen darf.') }}</span>
        </div>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Kommentare --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <i class="fal fa-comments text-[var(--muted)]"></i>
                    <h2 class="font-semibold text-[var(--ink)]">
                        Kommentare
                    </h2>
                </div>

                {{-- Name --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-[var(--ink)] mb-1">
                        <i class="fal fa-user text-[var(--muted)]"></i>
                        <span>Name anzeigen für</span>
                    </label>

                    <select
                        wire:model.defer="privacy.comments.name_visibility"
                        class="block w-full rounded-xl border-[var(--line)] p-3 pr-10
                               focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                        <option value="all">Alle</option>
                        <option value="users">Nur eingeloggte Benutzer</option>
                        <option value="none">Niemand</option>
                    </select>
                </div>

                {{-- Avatar --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-[var(--ink)] mb-1">
                        <i class="fal fa-image text-[var(--muted)]"></i>
                        <span>Profilbild anzeigen für</span>
                    </label>

                    <select
                        wire:model.defer="privacy.comments.avatar_visibility"
                        class="block w-full rounded-xl border-[var(--line)] p-3 pr-10
                               focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                        <option value="all">Alle</option>
                        <option value="users">Nur eingeloggte Benutzer</option>
                        <option value="none">Niemand</option>
                    </select>
                </div>
            </div>

            {{-- Bewertungen --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <i class="fal fa-star-half-alt text-[var(--muted)]"></i>
                    <h2 class="font-semibold text-[var(--ink)]">
                        Bewertungen
                    </h2>
                </div>

                {{-- Name --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-[var(--ink)] mb-1">
                        <i class="fal fa-user text-[var(--muted)]"></i>
                        <span>Name anzeigen für</span>
                    </label>

                    <select
                        wire:model.defer="privacy.ratings.name_visibility"
                        class="block w-full rounded-xl border-[var(--line)] p-3 pr-10
                               focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                        <option value="all">Alle</option>
                        <option value="users">Nur eingeloggte Benutzer</option>
                        <option value="none">Niemand</option>
                    </select>
                </div>

                {{-- Avatar --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-[var(--ink)] mb-1">
                        <i class="fal fa-image text-[var(--muted)]"></i>
                        <span>Profilbild anzeigen für</span>
                    </label>

                    <select
                        wire:model.defer="privacy.ratings.avatar_visibility"
                        class="block w-full rounded-xl border-[var(--line)] p-3 pr-10
                               focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                        <option value="all">Alle</option>
                        <option value="users">Nur eingeloggte Benutzer</option>
                        <option value="none">Niemand</option>
                    </select>
                </div>
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

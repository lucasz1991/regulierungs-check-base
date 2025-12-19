<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        <div class="flex items-center gap-2">
            <i class="fal fa-user-cog text-primary"></i>
            <span>{{ __('Profilinformationen') }}</span>
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="flex items-start gap-2">
            <span>{{ __('Aktualisiere die Profilinformationen und die E-Mail-Adresse deines Kontos.') }}</span>
        </div>
    </x-slot>

    <x-slot name="form">
        {{-- Profilfoto --}}
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div
                x-data="{
                    photoName: null,
                    photoPreview: null,
                    resizedPhoto: null,
                    resizeImage(file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const img = new Image();
                            img.onload = () => {
                                const canvas = document.createElement('canvas');
                                const maxSize = 200;
                                let width = img.width;
                                let height = img.height;

                                if (width > height && width > maxSize) {
                                    height *= maxSize / width;
                                    width = maxSize;
                                } else if (height > width && height > maxSize) {
                                    width *= maxSize / height;
                                    height = maxSize;
                                } else if (width > maxSize) {
                                    width = maxSize;
                                    height = maxSize;
                                }

                                canvas.width = width;
                                canvas.height = height;

                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(img, 0, 0, width, height);

                                this.resizedPhoto = canvas.toDataURL('image/jpeg', 0.8);
                                this.photoPreview = this.resizedPhoto;
                            };
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }"
                class="col-span-6 pb-6 border-b border-gray-200"
            >
                {{-- Hidden input (dein Handling bleibt) --}}
                <input
                    type="file"
                    id="photo"
                    class="hidden"
                    wire:model.live="photo"
                    x-ref="photo"
                    x-on:change="
                        photoName = $refs.photo.files[0].name;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            photoPreview = e.target.result;
                        };
                        reader.readAsDataURL($refs.photo.files[0]);
                    "
                />

                <div class="flex items-start justify-between gap-6">
                    <div class="min-w-0 text-primary">
                        <x-label for="photo" value="{{ __('Foto') }}" />

                        <p class="mt-1 text-sm text-gray-600">
                            Lade ein quadratisches Profilbild hoch.
                        </p>

                        <template x-if="photoName">
                            <p class="mt-2 text-xs text-gray-500 truncate">
                                <i class="fal fa-image mr-1 text-gray-400"></i>
                                <span x-text="photoName"></span>
                            </p>
                        </template>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium
                                       bg-primary text-white shadow-lg shadow-primary/20 hover:opacity-95 transition
                                       focus:outline-none focus:ring-2 focus:ring-primary/40"
                                x-on:click.prevent="$refs.photo.click()"
                            >
                                <i class="fal fa-upload"></i>
                                {{ __('Neues Foto auswählen') }}
                            </button>

                            @if ($this->user->profile_photo_path)
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium
                                           text-red-600 hover:text-red-700 transition
                                           focus:outline-none focus:ring-2 focus:ring-red-200"
                                    wire:click="deleteProfilePhoto"
                                >
                                    <i class="fal fa-trash-alt"></i>
                                    {{ __('Foto entfernen') }}
                                </button>
                            @endif
                        </div>

                        <x-input-error for="photo" class="mt-3" />
                    </div>

                    {{-- Avatar --}}
                    <div class="shrink-0 relative">
                        <div class="absolute -inset-1 rounded-full bg-primary/20 blur-[2px]"></div>

                        {{-- Aktuelles Foto --}}
                        <div class="relative" x-show="! photoPreview">
                            <img
                                src="{{ $this->user->profile_photo_url }}"
                                alt="{{ $this->user->name }}"
                                class="rounded-full h-20 w-20 object-cover ring-4 ring-white shadow"
                            >
                        </div>

                        {{-- Preview --}}
                        <div class="relative" x-show="photoPreview" style="display:none;">
                            <span
                                class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center ring-4 ring-white shadow"
                                x-bind:style="'background-image: url(\'' + photoPreview + '\');'"
                            ></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Benutzername --}}
        <div class="col-span-6 py-6 border-b border-gray-200">
            <div class="flex items-center gap-2 mb-2">
                <i class="fal fa-user text-primary"></i>
                <x-label for="name" value="{{ __('Benutzername') }}" />
            </div>

            <div class="relative">

                <x-input
                    id="name"
                    type="text"
                    class="block w-full rounded-xl border-gray-300 p-3 pl-11
                           focus:ring-2 focus:ring-primary focus:border-primary"
                    wire:model="state.name"
                    required
                    autocomplete="name"
                    placeholder="Dein Benutzername"
                />
            </div>

            <x-input-error for="name" class="mt-2" />
        </div>

        {{-- E-Mail --}}
        <div class="col-span-6 pt-6">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <i class="fal fa-envelope text-primary"></i>
                        <x-label for="email" value="{{ __('E-Mail') }}" />
                    </div>

                    <p class="mt-1 text-sm text-gray-600">
                        Deine E-Mail wird für Login & Benachrichtigungen genutzt.
                    </p>
                </div>

                {{-- Status --}}
                <div class="shrink-0">
                    @if ($this->user->hasVerifiedEmail())
                        <span class="inline-flex items-center gap-2 text-emerald-700 text-xs font-semibold">
                            <i class="fal fa-check-circle"></i>
                            verifiziert
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 text-amber-700 text-xs font-semibold">
                            <i class="fal fa-exclamation-circle"></i>
                            nicht verifiziert
                        </span>
                    @endif
                </div>
            </div>

            <div class="relative mt-3">
                <i class="fal fa-at absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <x-input
                    id="email"
                    type="email"
                    class="block w-full rounded-xl border-gray-300 p-3 pl-11
                           focus:ring-2 focus:ring-primary focus:border-primary
                           {{ $this->user->hasVerifiedEmail() ? 'ring-1 ring-emerald-200' : 'ring-1 ring-amber-200' }}"
                    wire:model="state.email"
                    required
                    autocomplete="username"
                    placeholder="name@beispiel.de"
                />
            </div>

            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    <div class="flex items-start gap-3">
                        <i class="fal fa-envelope-open-text mt-0.5 text-amber-600"></i>
                        <div>
                            <div class="font-semibold">
                                {{ __('Deine E-Mail-Adresse ist nicht verifiziert.') }}
                            </div>

                            <button
                                type="button"
                                class="mt-2 inline-flex items-center gap-2 text-sm font-medium text-primary hover:underline
                                       focus:outline-none focus:ring-2 focus:ring-primary/30 rounded"
                                wire:click.prevent="sendEmailVerification"
                            >
                                <i class="fal fa-paper-plane"></i>
                                {{ __('Verifizierungs-E-Mail erneut senden') }}
                            </button>

                            @if ($this->verificationLinkSent)
                                <div class="mt-2 text-emerald-700 font-medium">
                                    {{ __('Ein neuer Verifizierungslink wurde an deine E-Mail-Adresse gesendet.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif ($this->user->hasVerifiedEmail())
                <p class="text-sm mt-3 text-emerald-700 font-medium flex items-center gap-2">
                    <i class="fal fa-check"></i>
                    {{ __('Deine E-Mail-Adresse ist verifiziert.') }}
                </p>
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Gespeichert.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            <i class="fal fa-save mr-2"></i>
            {{ __('Speichern') }}
        </x-button>
    </x-slot>
</x-form-section>

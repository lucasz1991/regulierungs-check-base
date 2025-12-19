<x-action-section>
    <x-slot name="title">
        <div class="flex items-center gap-2">
            <i class="fal fa-browser text-primary"></i>
            <span>{{ __('Browsersitzungen') }}</span>
        </div>
    </x-slot>

    <x-slot name="description">
        <span class="text-gray-600">
            {{ __('Verwalte und melde dich von deinen aktiven Sitzungen in anderen Browsern und Geräten ab.') }}
        </span>
    </x-slot>

    <x-slot name="content">
        <div class="text-sm text-gray-600 max-w-2xl">
            {{ __('Falls erforderlich, kannst du dich von allen deinen anderen Browsersitzungen auf allen Geräten abmelden. Einige deiner letzten Sitzungen sind unten aufgeführt; diese Liste ist jedoch möglicherweise nicht vollständig. Wenn du glaubst, dass dein Konto kompromittiert wurde, solltest du auch dein Passwort ändern.') }}
        </div>

        @if (count($this->sessions) > 0)
            <div class="mt-6 space-y-4">
                @foreach ($this->sessions as $session)
                    <div class="flex items-start gap-4 rounded-xl bg-gray-50 border border-gray-200 p-4">

                        {{-- Device Icon --}}
                        <div class="shrink-0">
                            <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                                @if ($session->agent->isDesktop())
                                    <i class="fal fa-desktop text-gray-500"></i>
                                @else
                                    <i class="fal fa-mobile-alt text-gray-500"></i>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $session->agent->platform() ?: __('Unbekannt') }}
                                        <span class="text-gray-400">•</span>
                                        {{ $session->agent->browser() ?: __('Unbekannt') }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500 flex items-center gap-1">
                                        <i class="fal fa-location-dot text-gray-400"></i>
                                        {{ $session->ip_address }}
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    @if ($session->is_current_device)
                                        <span class="inline-flex items-center gap-2 rounded-full
                                                     bg-emerald-50 text-emerald-700
                                                     px-3 py-1 text-xs font-semibold">
                                            <i class="fal fa-check-circle"></i>
                                            {{ __('Dieses Gerät') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full
                                                     bg-gray-100 text-gray-600
                                                     px-3 py-1 text-xs font-medium">
                                            <i class="fal fa-clock"></i>
                                            {{ __('Zuletzt aktiv') }} {{ $session->last_active }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

        {{-- Actions --}}
        <div class="mt-6 flex flex-wrap items-center gap-3">
            <x-button wire:click="confirmLogout" wire:loading.attr="disabled">
                <i class="fal fa-right-from-bracket mr-2"></i>
                {{ __('Von anderen Browsersitzungen abmelden') }}
            </x-button>

            <x-action-message class="ms-1 text-gray-600" on="loggedOut">
                <span class="inline-flex items-center gap-2">
                    <i class="fal fa-check"></i>
                    {{ __('Erledigt.') }}
                </span>
            </x-action-message>
        </div>

        {{-- Modal --}}
        <x-dialog-modal wire:model.live="confirmingLogout">
            <x-slot name="title">
                <div class="flex items-center gap-2">
                    <i class="fal fa-user-slash text-primary"></i>
                    <span>{{ __('Von anderen Browsersitzungen abmelden') }}</span>
                </div>
            </x-slot>

            <x-slot name="content">
                <p class="text-sm text-gray-700">
                    {{ __('Bitte gib dein Passwort ein, um zu bestätigen, dass du dich von deinen anderen Browsersitzungen auf allen Geräten abmelden möchtest.') }}
                </p>

                <div class="mt-4" x-data="{}"
                     x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                        <i class="fal fa-lock text-gray-400"></i>
                        {{ __('Passwort') }}
                    </label>

                    <x-input
                        type="password"
                        class="block w-full rounded-xl border-gray-300 p-3
                               focus:ring-2 focus:ring-primary focus:border-primary"
                        autocomplete="current-password"
                        placeholder="{{ __('Passwort') }}"
                        x-ref="password"
                        wire:model="password"
                        wire:keydown.enter="logoutOtherBrowserSessions"
                    />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled">
                    <i class="fal fa-times mr-2"></i>
                    {{ __('Abbrechen') }}
                </x-secondary-button>

                <x-button class="ms-3"
                          wire:click="logoutOtherBrowserSessions"
                          wire:loading.attr="disabled">
                    <i class="fal fa-right-from-bracket mr-2"></i>
                    {{ __('Abmelden') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>

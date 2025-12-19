<x-action-section>
    <x-slot name="title">
        <div class="flex items-center gap-2">
            <i class="fal fa-lock text-primary"></i>
            <span>{{ __('Zwei-Faktor-Authentifizierung') }}</span>
        </div>
    </x-slot>

    <x-slot name="description">
        <span class="text-gray-600">
            {{ __('Erhöhe die Sicherheit deines Kontos, indem du eine zusätzliche Bestätigung beim Login aktivierst.') }}
        </span>
    </x-slot>

    <x-slot name="content">
        {{-- Status --}}
        <div class="flex items-start gap-3">
            @if ($this->enabled)
                <i class="fal fa-lock text-emerald-600 mt-1"></i>
            @else
                <i class="fal fa-lock-open text-amber-600 mt-1"></i>
            @endif

            <div class="min-w-0">
                <h3 class="text-lg font-semibold text-gray-900">
                    @if ($this->enabled)
                        @if ($showingConfirmation)
                            {{ __('Beende die Aktivierung der Zwei-Faktor-Authentifizierung') }}
                        @else
                            {{ __('Zwei-Faktor-Authentifizierung ist aktiviert') }}
                        @endif
                    @else
                        {{ __('Zwei-Faktor-Authentifizierung ist deaktiviert') }}
                    @endif
                </h3>

                <p class="mt-2 text-sm text-gray-600 max-w-xl">
                    {{ __('Bei aktivierter Zwei-Faktor-Authentifizierung wirst du beim Login nach einem zusätzlichen Sicherheitscode gefragt, der z. B. über eine Authenticator-App generiert wird.') }}
                </p>
            </div>
        </div>

        {{-- QR Code / Setup --}}
        @if ($this->enabled && $showingQrCode)
            <div class="mt-6 space-y-4">

                <div class="text-sm text-gray-700 max-w-xl">
                    <p class="font-medium text-gray-900">
                        @if ($showingConfirmation)
                            {{ __('Scanne den QR-Code mit deiner Authenticator-App und gib anschließend den generierten Code ein, um die Einrichtung abzuschließen.') }}
                        @else
                            {{ __('Scanne den QR-Code mit deiner Authenticator-App oder verwende den Setup-Schlüssel.') }}
                        @endif
                    </p>
                </div>

                {{-- QR Code (bewusst weiß) --}}
                <div class="inline-block rounded-xl bg-white p-3 shadow border border-gray-200">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                {{-- Setup Key --}}
                <div class="text-sm text-gray-700">
                    <span class="font-semibold text-gray-900 inline-flex items-center gap-2">
                        <i class="fal fa-key text-gray-400"></i>
                        {{ __('Setup-Schlüssel') }}:
                    </span>
                    <span class="font-mono text-emerald-700 break-all">
                        {{ decrypt($this->user->two_factor_secret) }}
                    </span>
                </div>

                {{-- Confirmation Code --}}
                @if ($showingConfirmation)
                    <div class="mt-4 max-w-xs">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                            <i class="fal fa-key text-gray-400"></i>
                            {{ __('Bestätigungscode') }}
                        </label>

                        <x-input
                            id="code"
                            type="text"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            autofocus
                            wire:model="code"
                            wire:keydown.enter="confirmTwoFactorAuthentication"
                            class="block w-full rounded-xl border-gray-300 p-3
                                   focus:ring-2 focus:ring-primary focus:border-primary"
                        />

                        <x-input-error for="code" class="mt-2" />
                    </div>
                @endif
            </div>
        @endif

        {{-- Recovery Codes --}}
        @if ($this->enabled && $showingRecoveryCodes)
            <div class="mt-6">
                <p class="text-sm text-gray-900 font-semibold mb-3">
                    {{ __('Bewahre diese Wiederherstellungscodes sicher auf. Sie werden benötigt, falls du den Zugriff auf dein Authenticator-Gerät verlierst.') }}
                </p>

                <div class="grid gap-1 max-w-xl px-4 py-4 font-mono text-sm
                            rounded-xl bg-gray-50 border border-gray-200 text-gray-800">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="mt-6 flex flex-wrap gap-3">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-button>
                        <i class="fal fa-shield-plus mr-2"></i>
                        {{ __('Aktivieren') }}
                    </x-button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <x-secondary-button>
                            <i class="fal fa-sync-alt mr-2"></i>
                            {{ __('Wiederherstellungscodes neu generieren') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                        <x-button>
                            <i class="fal fa-check mr-2"></i>
                            {{ __('Bestätigen') }}
                        </x-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <x-secondary-button>
                            <i class="fal fa-eye mr-2"></i>
                            {{ __('Wiederherstellungscodes anzeigen') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-secondary-button>
                            <i class="fal fa-times mr-2"></i>
                            {{ __('Abbrechen') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-danger-button>
                            <i class="fal fa-trash mr-2"></i>
                            {{ __('Deaktivieren') }}
                        </x-danger-button>
                    </x-confirms-password>
                @endif
            @endif
        </div>
    </x-slot>
</x-action-section>

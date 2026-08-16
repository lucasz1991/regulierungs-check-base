<?php

namespace App\Services\Auth;

use App\Services\Promotion\SocialAuthProviderSettingsService;

final class SocialiteRuntimeConfigurator
{
    public function __construct(private readonly SocialAuthProviderSettingsService $settings) {}

    /** @return list<string> */
    public function availableProviders(): array
    {
        return collect($this->settings->all())
            ->filter(fn (array $setting): bool => $setting['enabled'] === true)
            ->keys()
            ->values()
            ->all();
    }

    /** @return array<string, string> */
    public function configure(string $provider): array
    {
        $credentials = $this->settings->credentials($provider);
        $snapshot = $this->settings->get($provider);
        $configuration = [
            'client_id' => $credentials['client_id'],
            'client_secret' => $credentials['client_secret'],
            'redirect' => $credentials['redirect_uri'],
        ];

        if ($provider === 'apple') {
            $configuration['team_id'] = (string) $snapshot['apple_team_id'];
            $configuration['key_id'] = (string) $snapshot['apple_key_id'];
        }

        config()->set('services.'.$provider, $configuration);

        return $configuration;
    }
}

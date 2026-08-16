<?php

namespace Tests\Feature\Promotion;

use App\Models\SocialAuthProviderSetting;
use App\Models\User;
use App\Services\Promotion\PromotionSettingsService;
use App\Services\Promotion\SocialAuthProviderSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class SocialAuthProviderSettingsServiceTest extends TestCase
{
    use RefreshDatabase;

    private SocialAuthProviderSettingsService $settings;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        self::assertSame('sqlite', DB::getDriverName());
        self::assertSame(':memory:', config('database.connections.sqlite.database'));

        app(PromotionSettingsService::class)->save([
            'enabled' => false,
            'redemption_base_url' => 'https://promotion.example.test',
            'qr_ttl_minutes' => 30,
        ]);
        $this->admin = User::factory()->create(['role' => 'admin', 'status' => true]);
        $this->settings = app(SocialAuthProviderSettingsService::class);
    }

    public function test_disabled_provider_can_be_saved_incompletely_without_exposing_a_secret(): void
    {
        $snapshot = $this->settings->save('google', [
            'enabled' => false,
            'client_id' => 'staged-google-client',
        ], $this->admin);

        self::assertFalse($snapshot['enabled']);
        self::assertFalse($snapshot['requested_enabled']);
        self::assertFalse($snapshot['has_client_secret']);
        self::assertFalse($snapshot['is_configured']);
        self::assertSame('staged-google-client', $snapshot['client_id']);
    }

    public function test_omitted_secret_and_apple_expiry_are_retained_during_staged_updates(): void
    {
        $expiry = now()->addMonths(3)->utc()->startOfSecond()->format('Y-m-d H:i:s').' UTC';
        $expectedExpiry = \Illuminate\Support\Carbon::parse($expiry)->utc()->format('Y-m-d H:i:s');
        $this->settings->save('apple', [
            'enabled' => false,
            'client_id' => 'services-id',
            'client_secret' => 'temporary-signed-apple-secret',
            'redirect_uri' => 'https://promotion.example.test/auth/apple/callback',
            'apple_team_id' => 'TEAM123',
            'apple_key_id' => 'KEY123',
            'client_secret_expires_at' => $expiry,
        ], $this->admin);

        $this->settings->save('apple', [
            'enabled' => false,
            'client_id' => 'services-id-updated',
            'client_secret' => '',
        ], $this->admin);

        $stored = SocialAuthProviderSetting::query()->where('provider', 'apple')->firstOrFail();
        self::assertNotSame('', (string) $stored->getRawOriginal('client_secret_encrypted'));
        self::assertSame($expectedExpiry, $stored->getRawOriginal('client_secret_expires_at'));

        $enabled = $this->settings->save('apple', ['enabled' => true], $this->admin);
        self::assertTrue($enabled['enabled']);
        self::assertSame('temporary-signed-apple-secret', $this->settings->credentials('apple')['client_secret']);
    }

    public function test_configuration_mac_detects_direct_provider_changes_fail_closed(): void
    {
        $this->settings->save('google', [
            'enabled' => true,
            'client_id' => 'google-client',
            'client_secret' => 'google-secret',
            'redirect_uri' => 'https://promotion.example.test/auth/google/callback',
        ], $this->admin);

        DB::table('social_auth_provider_settings')->where('provider', 'google')->update(['client_id' => 'tampered-client']);

        $snapshot = $this->settings->get('google');
        self::assertFalse($snapshot['enabled']);
        self::assertStringContainsString('ausserhalb', (string) $snapshot['configuration_error']);

        $this->expectException(RuntimeException::class);
        $this->settings->credentials('google');
    }
}

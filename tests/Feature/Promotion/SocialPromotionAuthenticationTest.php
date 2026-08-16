<?php

namespace Tests\Feature\Promotion;

use App\Livewire\Auth\Register;
use App\Models\Customer;
use App\Models\SocialAuthProviderSetting;
use App\Models\SocialAccount;
use App\Models\Team;
use App\Models\User;
use App\Services\Promotion\PromotionSettingsService;
use App\Services\Promotion\SocialAuthProviderSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class SocialPromotionAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        app(PromotionSettingsService::class)->save([
            'enabled' => false,
            'redemption_base_url' => 'https://promotion.example.test',
            'qr_ttl_minutes' => 30,
        ]);
        $this->admin = User::factory()->create(['role' => 'admin', 'status' => true]);
    }

    public function test_verified_google_identity_creates_full_customer_account_without_tokens(): void
    {
        $this->configureGoogle();
        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($this->socialUser([
            'sub' => 'google-4711',
            'email' => 'google@example.test',
            'name' => 'Google Teilnehmer',
            'email_verified' => true,
        ]));
        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($driver);

        $this->get(route('social.callback', ['provider' => 'google']))
            ->assertRedirect('/dashboard');

        $user = User::query()->where('email', 'google@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertTrue(Customer::query()->where('user_id', $user->id)->exists());
        $this->assertTrue($user->teams()->where('name', 'Benutzer')->exists());
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_user_id' => 'google-4711',
        ]);
        $this->assertFalse(Schema::hasColumn('social_accounts', 'access_token'));
        $this->assertFalse(Schema::hasColumn('social_accounts', 'refresh_token'));
    }

    public function test_existing_account_is_linked_only_after_its_email_is_verified(): void
    {
        $this->configureGoogle();
        $existing = User::factory()->unverified()->create(['email' => 'existing@example.test']);
        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($this->socialUser([
            'sub' => 'google-existing',
            'email' => $existing->email,
            'name' => $existing->name,
            'email_verified' => true,
        ]));
        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($driver);

        $this->get(route('social.callback', ['provider' => 'google']))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('social');

        $this->assertGuest();
        $this->assertDatabaseMissing('social_accounts', ['provider_user_id' => 'google-existing']);
    }

    public function test_verified_existing_account_is_linked_without_duplicate_user_or_customer_profile(): void
    {
        $this->configureGoogle();
        $existing = User::factory()->create(['email' => 'linked@example.test']);
        Customer::query()->create([
            'user_id' => $existing->id,
            'first_name' => 'Bereits',
            'last_name' => 'Vorhanden',
            'username' => 'Bestehendes Profil',
            'phone_number' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => '',
        ]);
        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($this->socialUser([
            'sub' => 'google-linked-existing',
            'email' => $existing->email,
            'name' => 'Abweichender Providername',
            'email_verified' => true,
            'token' => 'must-not-be-stored-access-token',
            'refresh_token' => 'must-not-be-stored-refresh-token',
        ]));
        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($driver);

        $this->get(route('social.callback', ['provider' => 'google']))
            ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($existing);
        $this->assertSame(1, User::query()->where('email', $existing->email)->count());
        $this->assertSame(1, Customer::query()->where('user_id', $existing->id)->count());
        $this->assertSame(
            Team::query()->where('name', 'Benutzer')->value('id'),
            $existing->fresh()->current_team_id,
        );
        $this->assertTrue($existing->fresh()->teams()->where('name', 'Benutzer')->exists());
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $existing->id,
            'provider' => 'google',
            'provider_user_id' => 'google-linked-existing',
        ]);
        $this->assertFalse(Schema::hasColumn('social_accounts', 'access_token'));
        $this->assertFalse(Schema::hasColumn('social_accounts', 'refresh_token'));
    }

    public function test_social_email_link_never_authenticates_admin_or_staff_accounts(): void
    {
        $this->configureGoogle();

        foreach (['admin', 'staff'] as $role) {
            $privileged = User::factory()->create([
                'email' => "{$role}-social@example.test",
                'role' => $role,
                'status' => true,
            ]);
            $driver = Mockery::mock();
            $driver->shouldReceive('user')->once()->andReturn($this->socialUser([
                'sub' => "google-{$role}",
                'email' => $privileged->email,
                'name' => $privileged->name,
                'email_verified' => true,
            ]));
            Socialite::shouldReceive('driver')->with('google')->once()->andReturn($driver);

            $this->get(route('social.callback', ['provider' => 'google']))
                ->assertRedirect(route('login'))
                ->assertSessionHasErrors('social');

            $this->assertGuest();
            $this->assertDatabaseMissing('social_accounts', ['provider_user_id' => "google-{$role}"]);
        }
    }

    public function test_existing_social_identity_stops_working_after_account_is_promoted(): void
    {
        $this->configureGoogle();
        $user = User::factory()->create([
            'email' => 'promoted-social@example.test',
            'role' => 'staff',
            'status' => true,
        ]);
        SocialAccount::query()->create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_user_id' => 'google-promoted',
            'provider_email' => $user->email,
        ]);
        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($this->socialUser([
            'sub' => 'google-promoted',
            'email' => $user->email,
            'name' => $user->name,
            'email_verified' => true,
        ]));
        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($driver);

        $this->get(route('social.callback', ['provider' => 'google']))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('social');

        $this->assertGuest();
    }

    public function test_verified_apple_identity_creates_full_customer_account_without_tokens(): void
    {
        $this->configureApple();
        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($this->socialUser([
            'sub' => 'apple-4711',
            'email' => 'apple-participant@example.test',
            'name' => 'Apple Teilnehmer',
            'email_verified' => 'true',
            'token' => 'must-not-be-stored-access-token',
            'refresh_token' => 'must-not-be-stored-refresh-token',
        ]));
        Socialite::shouldReceive('driver')->with('apple')->once()->andReturn($driver);

        $this->post(route('social.callback', ['provider' => 'apple']))
            ->assertRedirect('/dashboard');

        $user = User::query()->where('email', 'apple-participant@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertTrue(Customer::query()->where('user_id', $user->id)->exists());
        $this->assertTrue($user->teams()->where('name', 'Benutzer')->exists());
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'apple',
            'provider_user_id' => 'apple-4711',
            'provider_email' => 'apple-participant@example.test',
        ]);
        $this->assertFalse(Schema::hasColumn('social_accounts', 'access_token'));
        $this->assertFalse(Schema::hasColumn('social_accounts', 'refresh_token'));
    }

    public function test_apple_redirect_temporarily_uses_a_secure_same_site_none_session_cookie(): void
    {
        $this->configureApple();
        $driver = Mockery::mock();
        $driver->shouldReceive('redirect')->once()->andReturn(redirect()->away('https://appleid.apple.com/auth/authorize'));
        Socialite::shouldReceive('driver')->with('apple')->once()->andReturn($driver);

        $response = $this->get(route('social.redirect', [
            'provider' => 'apple',
            'return_to' => '/gluecksrad',
        ]));

        $response->assertRedirect('https://appleid.apple.com/auth/authorize')
            ->assertSessionHas('social_auth_return_to', '/gluecksrad');

        $sessionCookie = collect($response->headers->getCookies())
            ->first(fn ($cookie): bool => $cookie->getName() === config('session.cookie'));

        $this->assertNotNull($sessionCookie);
        $this->assertTrue($sessionCookie->isSecure());
        $this->assertSame('none', mb_strtolower((string) $sessionCookie->getSameSite()));
    }

    public function test_social_login_stays_successful_when_promotion_ticket_is_not_available_yet(): void
    {
        $this->configureGoogle();
        session(['social_auth_return_to' => '/gluecksrad']);
        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($this->socialUser([
            'sub' => 'google-wheel',
            'email' => 'wheel@example.test',
            'name' => 'Wheel User',
            'email_verified' => true,
        ]));
        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($driver);

        $this->get(route('social.callback', ['provider' => 'google']))
            ->assertRedirect('/gluecksrad');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'wheel@example.test']);
        $this->assertDatabaseCount('promotion_tickets', 0);
    }

    public function test_provider_without_verified_email_claim_is_rejected_for_google_and_apple(): void
    {
        $this->configureGoogle();
        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($this->socialUser([
            'sub' => 'google-unverified',
            'email' => 'unverified@example.test',
            'name' => 'Unverified',
        ]));
        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($driver);

        $this->get(route('social.callback', ['provider' => 'google']))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('social');

        $this->assertDatabaseMissing('users', ['email' => 'unverified@example.test']);

        $this->configureApple();
        $appleDriver = Mockery::mock();
        $appleDriver->shouldReceive('user')->once()->andReturn($this->socialUser([
            'sub' => 'apple-unverified',
            'email' => 'apple@example.test',
            'name' => 'Apple User',
            'email_verified' => 'false',
        ]));
        Socialite::shouldReceive('driver')->with('apple')->once()->andReturn($appleDriver);

        $this->post(route('social.callback', ['provider' => 'apple']))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('social');
        $this->assertDatabaseMissing('users', ['email' => 'apple@example.test']);
    }

    public function test_tampered_provider_configuration_is_hidden_fail_closed(): void
    {
        $this->configureGoogle();
        SocialAuthProviderSetting::query()->where('provider', 'google')->update([
            'redirect_uri' => 'https://attacker.example/callback',
        ]);

        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee('Mit Google anmelden');
    }

    public function test_normal_livewire_registration_rejects_unaccepted_terms(): void
    {
        Livewire::test(Register::class)
            ->set('email', 'terms@example.test')
            ->set('username', 'Terms Test')
            ->set('password', 'Sicheres!Passwort1')
            ->set('password_confirmation', 'Sicheres!Passwort1')
            ->set('terms', false)
            ->call('register')
            ->assertHasErrors(['terms' => 'accepted']);

        $this->assertDatabaseMissing('users', ['email' => 'terms@example.test']);
    }

    private function configureGoogle(): void
    {
        app(SocialAuthProviderSettingsService::class)->save('google', [
            'enabled' => true,
            'client_id' => 'google-client-id',
            'client_secret' => 'google-client-secret',
            'redirect_uri' => 'https://promotion.example.test/auth/google/callback',
        ], $this->admin);
    }

    private function configureApple(): void
    {
        app(SocialAuthProviderSettingsService::class)->save('apple', [
            'enabled' => true,
            'client_id' => 'de.regulierungs-check.web',
            'client_secret' => 'signed-client-secret',
            'redirect_uri' => 'https://promotion.example.test/auth/apple/callback',
            'apple_team_id' => 'TEAM123',
            'apple_key_id' => 'KEY123',
            'client_secret_expires_at' => now()->addMonth()->toDateTimeString(),
        ], $this->admin);
    }

    private function socialUser(array $payload): object
    {
        return new class($payload)
        {
            public array $user;

            public ?string $token;

            public ?string $refreshToken;

            public function __construct(private readonly array $payload)
            {
                $this->user = $payload;
                $this->token = $payload['token'] ?? null;
                $this->refreshToken = $payload['refresh_token'] ?? null;
            }

            public function getEmail(): ?string
            {
                return $this->payload['email'] ?? null;
            }

            public function getId(): ?string
            {
                return $this->payload['sub'] ?? null;
            }

            public function getName(): ?string
            {
                return $this->payload['name'] ?? null;
            }
        };
    }
}

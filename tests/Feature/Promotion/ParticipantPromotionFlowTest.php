<?php

namespace Tests\Feature\Promotion;

use App\Http\Controllers\Participant\Promotion\RedemptionController;
use App\Jobs\LogActivityJob;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\Participant\Promotion\ParticipationShow;
use App\Models\Customer;
use App\Models\PromotionCampaign;
use App\Models\PromotionParticipation;
use App\Models\PromotionPrize;
use App\Models\User;
use App\Notifications\CustomVerifyEmail;
use App\Services\Promotion\PromotionWinService;
use App\Services\Promotion\PromotionAuditChain;
use App\Services\Promotion\PromotionSettingsService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class ParticipantPromotionFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PromotionSettingsService::class)->save([
            'enabled' => true,
            'redemption_base_url' => 'https://promotion.example.test',
            'qr_ttl_minutes' => 30,
        ]);
    }

    public function test_user_model_really_requires_email_verification(): void
    {
        $this->assertContains(MustVerifyEmail::class, class_implements(User::class));
    }

    public function test_registration_form_has_one_submit_path(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('wire:submit.prevent="register"', false)
            ->assertSee('type="submit"', false)
            ->assertDontSee('wire:click.prevent="register"', false);
    }

    public function test_scan_stores_token_only_in_session_and_redirects_to_clean_url(): void
    {
        [$issued] = $this->issueWin();
        Bus::fake();

        $response = $this->get(route('promotion.redeem', ['token' => $issued->plainToken]));

        $response
            ->assertRedirect(route('promotion.claim'))
            ->assertSessionHas(RedemptionController::TOKEN_SESSION_KEY, $issued->plainToken)
            ->assertHeader('Referrer-Policy', 'no-referrer')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        Bus::assertNotDispatched(LogActivityJob::class);

        $this->get(route('promotion.claim'))
            ->assertOk()
            ->assertDontSee($issued->plainToken)
            ->assertHeader('Referrer-Policy', 'no-referrer');
    }

    public function test_existing_account_can_bind_an_issued_win_once(): void
    {
        [$issued] = $this->issueWin();
        $user = User::factory()->unverified()->create(['role' => 'guest']);

        $response = $this->actingAs($user)->get(route('promotion.redeem', [
            'token' => $issued->plainToken,
        ]));

        $participation = PromotionParticipation::query()->where('user_id', $user->id)->firstOrFail();

        $response
            ->assertRedirect(route('promotion.participation.show', ['participation' => $participation->public_id]))
            ->assertSessionMissing(RedemptionController::TOKEN_SESSION_KEY);
        $this->assertSame('bound', $this->statusValue($participation->fresh()->status));
    }

    public function test_inactive_account_cannot_login_or_bind_a_win(): void
    {
        [$issued] = $this->issueWin();
        $user = User::factory()->create(['role' => 'guest', 'status' => false]);

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasErrors('email');

        $this->expectException(\DomainException::class);
        app(PromotionWinService::class)->bindToken($issued->plainToken, $user);
    }

    public function test_inactive_account_cannot_use_fortify_login_or_an_existing_participation(): void
    {
        [$issued] = $this->issueWin();
        $user = User::factory()->create(['role' => 'guest', 'status' => true]);
        $participation = app(PromotionWinService::class)->bindToken($issued->plainToken, $user);
        $user->forceFill(['status' => false])->save();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->actingAs($user)
            ->get(route('promotion.participation.show', ['participation' => $participation->public_id]))
            ->assertForbidden();

        $this->expectException(\DomainException::class);
        app(PromotionWinService::class)->confirmParticipation($participation, $user);
    }

    public function test_internal_test_and_pagebuilder_routes_are_not_public(): void
    {
        $this->get('/admin/tools/tests/stream-chat')->assertNotFound();
        $this->postJson('/api/pagebuilder/upload')->assertNotFound();
        $this->getJson('/api/pagebuilder/assets')->assertNotFound();
    }

    public function test_logged_out_existing_account_is_bound_immediately_after_livewire_login(): void
    {
        [$issued] = $this->issueWin();
        $user = User::factory()->create(['role' => 'guest']);
        session()->put(RedemptionController::TOKEN_SESSION_KEY, $issued->plainToken);

        $component = Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors();

        $participation = $user->promotionParticipations()->firstOrFail();

        $component->assertRedirect(route('promotion.participation.show', ['participation' => $participation->public_id]));
        $this->assertAuthenticatedAs($user);
        $this->assertFalse(session()->has(RedemptionController::TOKEN_SESSION_KEY));
        $this->assertSame('bound', $this->statusValue($participation->status));
    }

    public function test_normal_livewire_registration_creates_customer_and_team_but_no_participation(): void
    {
        Notification::fake();

        Livewire::test(Register::class)
            ->set('email', 'normal@example.test')
            ->set('username', 'NormalerTeilnehmer')
            ->set('password', 'SehrSicher!123')
            ->set('password_confirmation', 'SehrSicher!123')
            ->set('terms', true)
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $user = User::query()->where('email', 'normal@example.test')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->customer()->exists());
        $this->assertTrue($user->teams()->where('name', 'Benutzer')->exists());
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertSame(0, $user->promotionParticipations()->count());
        Notification::assertSentTo($user, CustomVerifyEmail::class);
    }

    public function test_promotion_registration_is_atomic_and_redirects_to_participation(): void
    {
        [$issued] = $this->issueWin();
        Notification::fake();
        session()->put(RedemptionController::TOKEN_SESSION_KEY, $issued->plainToken);

        $component = Livewire::test(Register::class)
            ->set('email', 'gewinner@example.test')
            ->set('username', 'Gewinnerin')
            ->set('password', 'SehrSicher!123')
            ->set('password_confirmation', 'SehrSicher!123')
            ->set('terms', true)
            ->call('register')
            ->assertHasNoErrors()
            ->assertNotDispatched('showAlert');

        $user = User::query()->where('email', 'gewinner@example.test')->firstOrFail();
        $participation = $user->promotionParticipations()->firstOrFail();

        $component->assertRedirect(route('promotion.participation.show', ['participation' => $participation->public_id]));
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Customer::query()->where('user_id', $user->id)->exists());
        $this->assertTrue($user->teams()->where('name', 'Benutzer')->exists());
        $this->assertFalse(session()->has(RedemptionController::TOKEN_SESSION_KEY));
        Notification::assertSentTo($user, CustomVerifyEmail::class);
    }

    public function test_failed_promotion_binding_rolls_back_entire_registration(): void
    {
        session()->put(RedemptionController::TOKEN_SESSION_KEY, str_repeat('x', 43));

        Livewire::test(Register::class)
            ->set('email', 'rollback@example.test')
            ->set('username', 'RollbackUser')
            ->set('password', 'SehrSicher!123')
            ->set('password_confirmation', 'SehrSicher!123')
            ->set('terms', true)
            ->call('register')
            ->assertHasErrors('promotion');

        $this->assertGuest();
        $this->assertFalse(User::query()->where('email', 'rollback@example.test')->exists());
        $this->assertFalse(Customer::query()->where('username', 'RollbackUser')->exists());
    }

    public function test_unverified_owner_can_view_and_confirm_but_another_user_cannot_view(): void
    {
        [$issued] = $this->issueWin();
        $owner = User::factory()->unverified()->create(['role' => 'guest']);
        $otherUser = User::factory()->unverified()->create(['role' => 'guest']);
        $participation = app(PromotionWinService::class)->bindToken($issued->plainToken, $owner);

        $this->actingAs($owner)
            ->get(route('promotion.participation.show', ['participation' => $participation->public_id]))
            ->assertOk()
            ->assertSee($participation->public_id);

        Livewire::actingAs($owner)
            ->test(ParticipationShow::class, ['participation' => $participation])
            ->call('confirm')
            ->assertHasNoErrors();

        $this->assertSame('confirmed', $this->statusValue($participation->fresh()->status));
        $this->assertFalse($owner->fresh()->hasVerifiedEmail());

        $this->actingAs($otherUser)
            ->get(route('promotion.participation.show', ['participation' => $participation->public_id]))
            ->assertNotFound();
    }

    public function test_participant_views_keep_the_issued_prize_name_after_a_later_rename(): void
    {
        [$issued] = $this->issueWin();
        $owner = User::factory()->create(['role' => 'guest']);
        $participation = app(PromotionWinService::class)->bindToken($issued->plainToken, $owner);
        $snapshot = (string) $issued->win->fresh()->prize_name_snapshot;
        $renamedPrize = 'Nachtraeglich umbenannter Gewinn';

        $issued->win->prize()->update(['name' => $renamedPrize]);

        $this->actingAs($owner)
            ->get(route('promotion.participation.show', ['participation' => $participation->public_id]))
            ->assertOk()
            ->assertSee($snapshot)
            ->assertDontSee($renamedPrize);

        Livewire::actingAs($owner)
            ->test(Dashboard::class)
            ->assertSee($snapshot)
            ->assertDontSee($renamedPrize);
    }

    public function test_dashboard_displays_only_the_authenticated_users_participation(): void
    {
        [$ownersIssued, $campaign] = $this->issueWin();
        $owner = User::factory()->create(['role' => 'guest']);
        $otherUser = User::factory()->create(['role' => 'guest']);
        $ownersParticipation = app(PromotionWinService::class)->bindToken($ownersIssued->plainToken, $owner);

        $prize = PromotionPrize::query()->where('campaign_id', $campaign->id)->firstOrFail();
        $otherIssued = app(PromotionWinService::class)->issue($campaign, $prize, User::factory()->create([
            'role' => 'admin',
            'status' => true,
        ]));
        $otherParticipation = app(PromotionWinService::class)->bindToken($otherIssued->plainToken, $otherUser);

        Livewire::actingAs($owner)
            ->test(Dashboard::class)
            ->assertSee($ownersParticipation->public_id)
            ->assertDontSee($otherParticipation->public_id)
            ->assertSee('Meine Gewinne');
    }

    /**
     * @return array{0: object, 1: PromotionCampaign}
     */
    private function issueWin(): array
    {
        $staff = User::factory()->create(['role' => 'admin', 'status' => true]);
        $campaign = PromotionCampaign::create([
            'code' => 'STR26-'.strtoupper(fake()->unique()->bothify('??##')),
            'name' => 'Straßenpromotion 2026',
            'is_active' => true,
        ]);
        $prize = PromotionPrize::create([
            'campaign_id' => $campaign->id,
            'code' => 'AMZ20',
            'name' => 'Amazon-Gutschein 20 €',
            'fulfillment_mode' => 'external_admin',
            'quota' => 10,
            'is_active' => true,
        ]);

        app(PromotionAuditChain::class)->appendConfiguration($campaign, 'campaign.configured', [
            'campaign' => [
                'id' => (int) $campaign->id,
                'code' => (string) $campaign->code,
                'name_digest' => hash('sha256', (string) $campaign->name),
                'starts_at' => $campaign->getRawOriginal('starts_at'),
                'ends_at' => $campaign->getRawOriginal('ends_at'),
                'is_active' => (bool) $campaign->is_active,
            ],
            'prizes' => [[
                'id' => (int) $prize->id,
                'code' => (string) $prize->code,
                'name_digest' => hash('sha256', (string) $prize->name),
                'fulfillment_mode' => (string) $prize->getRawOriginal('fulfillment_mode'),
                'quota' => (int) $prize->quota,
                'reserved_count' => (int) $prize->reserved_count,
                'is_active' => (bool) $prize->is_active,
                'sort_order' => (int) $prize->sort_order,
                'configuration_digest' => hash('sha256', json_encode($prize->configuration, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES)),
            ]],
        ], $staff);

        return [app(PromotionWinService::class)->issue($campaign, $prize, $staff), $campaign];
    }

    private function statusValue(mixed $status): string
    {
        return $status instanceof \BackedEnum ? (string) $status->value : (string) $status;
    }
}

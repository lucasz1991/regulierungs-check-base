<?php

namespace Tests\Feature\Promotion;

use App\Livewire\Dashboard;
use App\Livewire\Participant\Promotion\WheelLanding;
use App\Mail\PromotionResultMail;
use App\Models\Customer;
use App\Models\PromotionCampaign;
use App\Models\PromotionParticipation;
use App\Models\PromotionPrize;
use App\Models\PromotionTicket;
use App\Models\User;
use App\Notifications\CustomVerifyEmail;
use App\Services\Promotion\PromotionAuditChain;
use App\Services\Promotion\PromotionResultMailer;
use App\Services\Promotion\PromotionSettingsService;
use App\Services\Promotion\PromotionTicketQrSigner;
use App\Services\Promotion\PromotionTicketService;
use App\Services\Promotion\PromotionTurnService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Support\CreatesPromotionParticipants;
use Tests\TestCase;

class ParticipantPromotionFlowTest extends TestCase
{
    use CreatesPromotionParticipants;
    use RefreshDatabase;

    private PromotionCampaign $campaign;

    private PromotionPrize $noWinPrize;

    private PromotionPrize $retryPrize;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        app(PromotionSettingsService::class)->save([
            'enabled' => true,
            'redemption_base_url' => 'https://promotion.example.test',
            'qr_ttl_minutes' => 30,
        ]);
        $this->admin = User::factory()->create(['role' => 'admin', 'status' => true]);
        $this->campaign = PromotionCampaign::query()->create([
            'code' => 'STR26',
            'name' => 'Straßenpromotion 2026',
            'landing_headline' => 'Dreh dein Glück',
            'landing_text' => 'Melde dich an und zeige dein Ticket.',
            'rules_text' => 'Ein Ticket je Konto.',
            'quota_exhaustion_policy' => 'block',
            'is_active' => true,
        ]);
        PromotionPrize::query()->create([
            'campaign_id' => $this->campaign->id,
            'code' => 'TEST-GEWINN',
            'name' => 'Testgewinn',
            'outcome_type' => 'prize',
            'fulfillment_mode' => 'onsite_staff',
            'quota' => 100,
            'is_active' => true,
            'sort_order' => 0,
        ]);
        $this->noWinPrize = PromotionPrize::query()->create([
            'campaign_id' => $this->campaign->id,
            'code' => 'NIETE',
            'name' => 'Niete',
            'outcome_type' => 'no_win',
            'fulfillment_mode' => 'onsite_staff',
            'quota' => 9999,
            'is_active' => true,
        ]);
        $this->retryPrize = PromotionPrize::query()->create([
            'campaign_id' => $this->campaign->id,
            'code' => 'ZUSATZ',
            'name' => 'Zusatzdreh',
            'outcome_type' => 'retry',
            'fulfillment_mode' => 'onsite_staff',
            'quota' => 9999,
            'is_active' => true,
            'sort_order' => 1,
        ]);
        app(PromotionAuditChain::class)->appendConfiguration(
            $this->campaign,
            'campaign.configured',
            app(PromotionAuditChain::class)->configurationPayload($this->campaign),
            $this->admin,
        );
        app(PromotionTicketService::class)->publishCampaign($this->campaign, $this->admin);
        $this->campaign->refresh();
    }

    public function test_poster_route_is_public_but_an_unverified_account_gets_no_ticket(): void
    {
        $this->get('/gluecksrad')
            ->assertOk()
            ->assertSee('Hol dir dein Dreh-Ticket')
            ->assertDontSee('wire:poll', false)
            ->assertHeader('Referrer-Policy', 'no-referrer');

        $user = $this->createPromotionParticipant(['email_verified_at' => null]);

        $this->actingAs($user)->get('/gluecksrad')
            ->assertOk()
            ->assertSee('E-Mail bestätigen')
            ->assertDontSee('wire:poll', false)
            ->assertDontSee('qr.svg');

        $this->assertDatabaseMissing('promotion_tickets', ['user_id' => $user->id]);
    }

    public function test_verified_account_receives_exactly_one_ticket_and_polling_is_idempotent(): void
    {
        $user = $this->createPromotionParticipant();

        $screen = Livewire::actingAs($user)
            ->test(WheelLanding::class)
            ->assertSee('Ticket bereit')
            ->assertSee('wire:poll.1000ms.visible', false)
            ->assertSet('ticketId', fn (?int $ticketId): bool => $ticketId !== null);

        $queries = [];
        DB::listen(static function ($query) use (&$queries): void {
            $queries[] = mb_strtolower((string) $query->sql);
        });

        $screen->call('refreshState')
            ->call('refreshState')
            ->assertHasNoErrors();

        $this->assertSame(1, PromotionTicket::query()->where('user_id', $user->id)->count());
        $this->assertSame(1, PromotionParticipation::query()->where('user_id', $user->id)->count());
        $this->assertFalse(collect($queries)->contains(
            static fn (string $sql): bool => str_contains($sql, 'promotion_audit_heads') || str_contains($sql, 'win_events'),
        ), 'Bestehende Tickets duerfen beim Polling keine vollstaendige Auditverifikation ausloesen.');
    }

    public function test_integrated_registration_waits_for_email_verification_before_creating_ticket(): void
    {
        Mail::fake();

        Livewire::test(WheelLanding::class)
            ->set('mode', 'register')
            ->set('email', 'teilnahme@example.test')
            ->set('username', 'TeilnahmeUser')
            ->set('password', 'Sicheres!Passwort1')
            ->set('password_confirmation', 'Sicheres!Passwort1')
            ->set('terms', true)
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect(route('promotion.wheel'));

        $user = User::query()->where('email', 'teilnahme@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Customer::query()->where('user_id', $user->id)->exists());
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertDatabaseMissing('promotion_tickets', ['user_id' => $user->id]);
        $this->get(route('promotion.wheel'))->assertOk()->assertSee('E-Mail bestätigen');

        $user->markEmailAsVerified();
        Livewire::actingAs($user)->test(WheelLanding::class)->call('refreshState')->assertSee('Ticket bereit');
        $this->assertDatabaseHas('promotion_tickets', ['user_id' => $user->id, 'status' => 'ready']);
    }

    public function test_verification_resend_is_rate_limited_server_side(): void
    {
        Notification::fake();
        $user = $this->createPromotionParticipant(['email_verified_at' => null]);

        Livewire::actingAs($user)
            ->test(WheelLanding::class)
            ->call('resendVerification')
            ->assertHasNoErrors()
            ->call('resendVerification')
            ->assertHasErrors('verification');

        Notification::assertSentToTimes($user, CustomVerifyEmail::class, 1);
    }

    public function test_integrated_login_is_rate_limited_by_email_and_ip(): void
    {
        $email = 'login-limit@example.test';
        User::factory()->create([
            'email' => $email,
            'password' => Hash::make('Sicheres!Passwort1'),
            'role' => 'guest',
        ]);

        $screen = Livewire::test(WheelLanding::class)
            ->set('email', $email)
            ->set('password', 'Falsch!Passwort1');

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $screen->call('login')->assertHasErrors('email');
        }

        $screen->set('password', 'Sicheres!Passwort1')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
        RateLimiter::clear('promotion-wheel-login:'.hash('sha256', $email.'|127.0.0.1'));
    }

    public function test_successful_integrated_login_redirects_to_a_fresh_ticket_page(): void
    {
        $user = $this->createPromotionParticipant([
            'email' => 'wheel-login@example.test',
            'password' => Hash::make('Sicheres!Passwort1'),
        ]);

        Livewire::test(WheelLanding::class)
            ->set('email', $user->email)
            ->set('password', 'Sicheres!Passwort1')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('promotion.wheel'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('promotion.wheel'))
            ->assertOk()
            ->assertSee('Ticket bereit')
            ->assertSee('wire:poll.1000ms.visible', false);
    }

    public function test_privileged_accounts_cannot_use_the_participant_password_login(): void
    {
        foreach (['admin', 'staff'] as $role) {
            $user = User::factory()->create([
                'email' => "wheel-{$role}@example.test",
                'password' => Hash::make('Sicheres!Passwort1'),
                'role' => $role,
                'status' => true,
            ]);

            Livewire::test(WheelLanding::class)
                ->set('email', $user->email)
                ->set('password', 'Sicheres!Passwort1')
                ->call('login')
                ->assertHasErrors('email');

            $this->assertGuest();
            $this->assertDatabaseMissing('promotion_tickets', ['user_id' => $user->id]);
        }
    }

    public function test_incomplete_guest_account_is_logged_out_instead_of_receiving_a_ticket(): void
    {
        $user = User::factory()->create([
            'email' => 'incomplete-wheel-user@example.test',
            'password' => Hash::make('Sicheres!Passwort1'),
            'role' => 'guest',
            'status' => true,
        ]);

        Livewire::test(WheelLanding::class)
            ->set('email', $user->email)
            ->set('password', 'Sicheres!Passwort1')
            ->call('login')
            ->assertRedirect(route('promotion.wheel'));

        $this->assertGuest();
        $this->assertDatabaseMissing('promotion_tickets', ['user_id' => $user->id]);
    }

    public function test_integrated_registration_is_rate_limited_by_email_and_ip(): void
    {
        $email = 'registration-limit@example.test';
        $key = 'promotion-wheel-register-email:'.hash('sha256', $email.'|127.0.0.1');
        for ($attempt = 0; $attempt < 3; $attempt++) {
            RateLimiter::hit($key, 600);
        }

        Livewire::test(WheelLanding::class)
            ->set('mode', 'register')
            ->set('email', $email)
            ->set('username', 'RegistrationLimit')
            ->set('password', 'Sicheres!Passwort1')
            ->set('password_confirmation', 'Sicheres!Passwort1')
            ->set('terms', true)
            ->call('register')
            ->assertHasErrors('registration');

        $this->assertDatabaseMissing('users', ['email' => $email]);
        RateLimiter::clear($key);
    }

    public function test_shared_venue_ip_allows_multiple_distinct_participants(): void
    {
        Notification::fake();
        $ipKey = 'promotion-wheel-register-ip:'.hash('sha256', '127.0.0.1');
        RateLimiter::clear($ipKey);

        for ($participant = 1; $participant <= 4; $participant++) {
            $email = "venue-participant-{$participant}@example.test";

            Livewire::test(WheelLanding::class)
                ->set('mode', 'register')
                ->set('email', $email)
                ->set('username', "VenueParticipant{$participant}")
                ->set('password', 'Sicheres!Passwort1')
                ->set('password_confirmation', 'Sicheres!Passwort1')
                ->set('terms', true)
                ->call('register')
                ->assertHasNoErrors()
                ->assertRedirect(route('promotion.wheel'));

            $this->assertDatabaseHas('users', ['email' => $email]);
            Auth::logout();
        }

        RateLimiter::clear($ipKey);
    }

    public function test_ticket_qr_is_owner_only_streamed_no_store_and_never_written_to_disk(): void
    {
        Storage::fake('local');
        $owner = $this->createPromotionParticipant();
        $other = $this->createPromotionParticipant();
        $ticket = app(PromotionTicketService::class)->ensureTicket($owner, $this->campaign);

        $response = $this->actingAs($owner)->get(route('promotion.ticket.qr', [
            'participation' => $ticket->participation->public_id,
        ]));

        $response->assertOk()->assertHeader('Content-Type', 'image/svg+xml; charset=UTF-8');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('<svg', $response->getContent());
        Storage::disk('local')->assertDirectoryEmpty('/');

        $this->flushSession()->actingAs($other)->get(route('promotion.ticket.qr', [
            'participation' => $ticket->participation->public_id,
        ]))->assertNotFound();

        $owner->forceFill(['role' => 'staff'])->save();
        $this->flushSession()->actingAs($owner)->get(route('promotion.ticket.qr', [
            'participation' => $ticket->participation->public_id,
        ]))->assertNotFound();
        $owner->forceFill(['role' => 'guest'])->save();

        $owner->forceFill(['email_verified_at' => null])->save();
        $this->flushSession()->actingAs($owner)->get(route('promotion.ticket.qr', [
            'participation' => $ticket->participation->public_id,
        ]))->assertRedirect(route('verification.notice'));
        Livewire::actingAs($owner)->test(WheelLanding::class)
            ->assertSee('E-Mail bestätigen')
            ->assertDontSee('wire:poll', false);
        $owner->forceFill(['email_verified_at' => now()])->save();

        $ticket->update(['status' => 'active']);
        $this->actingAs($owner)->get(route('promotion.ticket.qr', [
            'participation' => $ticket->participation->public_id,
        ]))->assertStatus(409);
    }

    public function test_participant_screen_follows_scan_retry_and_final_result_via_poll_state(): void
    {
        $user = $this->createPromotionParticipant();
        $ticket = app(PromotionTicketService::class)->ensureTicket($user, $this->campaign);
        $turns = app(PromotionTurnService::class);
        $turn = $turns->scanTicket(app(PromotionTicketQrSigner::class)->payload($ticket), $this->admin);

        Livewire::actingAs($user)->test(WheelLanding::class)
            ->assertSee('Du bist dran')
            ->assertDontSee('qr.svg');

        $turns->recordResult($turn, $this->retryPrize, 'retry', $this->admin);
        Livewire::actingAs($user)->test(WheelLanding::class)->assertSee('Zusatzdreh');

        $result = $turns->recordResult($turn, $this->noWinPrize, 'no_win', $this->admin);
        $screen = Livewire::actingAs($user)->test(WheelLanding::class)
            ->assertSee('Diesmal leider kein Gewinn')
            ->assertSee('wird zusätzlich per E-Mail versendet')
            ->assertSee('wire:poll.2000ms.visible', false);

        $turns->markMailFailed($result, 'SMTP test failure');
        $screen->call('refreshState')
            ->assertSee('E-Mail konnte nicht zugestellt werden')
            ->assertDontSee('und per E-Mail versendet');

        $result = $turns->markMailPendingForResend($result->fresh(), $this->admin);
        $turns->markMailSent($result);
        $screen->call('refreshState')->assertSee('und per E-Mail versendet');

        $this->travel(PromotionTurnService::CORRECTION_WINDOW_MINUTES + 1)->minutes();
        $screen->call('refreshState')->assertDontSee('wire:poll.2000ms.visible', false);

        Livewire::actingAs($user)->test(Dashboard::class)
            ->assertSee($ticket->participation->public_id)
            ->assertSee('Diesmal leider kein Gewinn');
    }

    public function test_active_turn_and_result_remain_visible_when_campaign_ends(): void
    {
        $this->travelTo(now()->startOfSecond());
        $this->campaign->update(['ends_at' => now()->addSeconds(5)]);
        app(PromotionAuditChain::class)->appendConfiguration(
            $this->campaign,
            'campaign.configured',
            app(PromotionAuditChain::class)->configurationPayload($this->campaign),
            $this->admin,
        );

        $user = $this->createPromotionParticipant();
        $ticket = app(PromotionTicketService::class)->ensureTicket($user, $this->campaign);
        $turns = app(PromotionTurnService::class);
        $turn = $turns->scanTicket(app(PromotionTicketQrSigner::class)->payload($ticket), $this->admin);
        $screen = Livewire::actingAs($user)->test(WheelLanding::class)
            ->assertSee('Du bist dran');

        $this->travel(6)->seconds();
        $screen->call('refreshState')
            ->assertSet('campaignId', $this->campaign->id)
            ->assertSee('Du bist dran')
            ->assertDontSee('Aktuell keine Aktion');

        $turns->recordResult($turn, $this->noWinPrize, 'no_win', $this->admin);
        $screen->call('refreshState')
            ->assertSee('Diesmal leider kein Gewinn')
            ->assertDontSee('Aktuell keine Aktion');
    }

    public function test_personal_qr_payload_contains_no_identity_and_tampering_is_rejected(): void
    {
        $user = $this->createPromotionParticipant();
        $ticket = app(PromotionTicketService::class)->ensureTicket($user, $this->campaign);
        $signer = app(PromotionTicketQrSigner::class);
        $payload = $signer->payload($ticket);

        $this->assertStringStartsWith('RC-TICKET-V1:'.$ticket->participation->public_id.':', $payload);
        $this->assertStringNotContainsString($user->email, $payload);
        $this->assertStringNotContainsString($user->name, $payload);
        $this->assertTrue($signer->parse($payload)->is($ticket));

        $this->expectException(\DomainException::class);
        $signer->parse(substr($payload, 0, -1).($payload[-1] === 'A' ? 'B' : 'A'));
    }

    public function test_final_result_mail_is_synchronous_and_updates_delivery_state(): void
    {
        Mail::fake();
        $user = $this->createPromotionParticipant();
        $ticket = app(PromotionTicketService::class)->ensureTicket($user, $this->campaign);
        $turns = app(PromotionTurnService::class);
        $turn = $turns->scanTicket(app(PromotionTicketQrSigner::class)->payload($ticket), $this->admin);
        $result = $turns->recordResult($turn, $this->noWinPrize, 'no_win', $this->admin);

        $this->assertTrue(app(PromotionResultMailer::class)->send($result));
        Mail::assertSent(PromotionResultMail::class, fn (PromotionResultMail $mail): bool => $mail->hasTo($user->email)
            && $mail->participantUrl === 'https://promotion.example.test/gluecksrad');
        $this->assertSame('sent', $result->fresh()->mail_status->value);
        $this->assertNotNull($result->fresh()->mail_sent_at);
    }

    public function test_mail_transport_failure_does_not_rollback_result_and_is_audited_as_failed(): void
    {
        $user = $this->createPromotionParticipant();
        $ticket = app(PromotionTicketService::class)->ensureTicket($user, $this->campaign);
        $turns = app(PromotionTurnService::class);
        $turn = $turns->scanTicket(app(PromotionTicketQrSigner::class)->payload($ticket), $this->admin);
        $result = $turns->recordResult($turn, $this->noWinPrize, 'no_win', $this->admin);
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('SMTP test failure'));

        $this->assertFalse(app(PromotionResultMailer::class)->send($result));

        $result->refresh();
        $this->assertSame('failed', $result->mail_status->value);
        $this->assertNotNull($result->mail_failed_at);
        $this->assertMatchesRegularExpression('/\A[a-f0-9]{64}\z/', (string) $result->mail_error_digest);
        $this->assertSame('completed', $ticket->fresh()->status->value);
        $this->assertTrue(app(PromotionAuditChain::class)->verify($this->campaign));
    }

    public function test_legacy_public_redemption_routes_are_gone(): void
    {
        $this->get('/promotion/einloesen/'.str_repeat('a', 43))->assertNotFound();
        $this->get('/promotion/gewinn-sichern')->assertNotFound();
        $this->get('/promotion/teilnahme/RC-OLD-0000-0000-0000-X')->assertNotFound();
    }
}

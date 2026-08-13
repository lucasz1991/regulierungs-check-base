<?php

namespace Tests\Feature\Promotion;

use App\Models\PromotionCampaign;
use App\Models\PromotionPrize;
use App\Models\PromotionWin;
use App\Models\PromotionWinEvent;
use App\Models\User;
use App\Services\Promotion\PromotionAuditChain;
use App\Services\Promotion\PromotionSettingsService;
use App\Services\Promotion\PromotionWinService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class PromotionAuditIntegrityTest extends TestCase
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

    public function test_append_fails_closed_after_audit_key_rotation(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        $service = app(PromotionWinService::class);
        $service->issue($campaign, $prize, $admin);
        $winCount = DB::table('wins')->count();
        $eventCount = DB::table('win_events')->count();

        DB::table('promotion_settings')->where('id', 1)->update(['audit_secret_encrypted' => 'rotated-outside-supported-flow']);

        try {
            $service->issue($campaign, $prize, $admin);
            $this->fail('Ein Append mit rotiertem Audit-Schluessel wurde akzeptiert.');
        } catch (\DomainException $exception) {
            $this->assertStringContainsString('deaktiviert', $exception->getMessage());
        }

        $this->assertSame($winCount, DB::table('wins')->count());
        $this->assertSame($eventCount, DB::table('win_events')->count());
        $this->assertSame(1, $prize->fresh()->reserved_count);
    }

    public function test_append_fails_closed_after_existing_event_tampering(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        $service = app(PromotionWinService::class);
        $service->issue($campaign, $prize, $admin);
        DB::table('win_events')->where('campaign_id', $campaign->id)->update(['event_type' => 'win.tampered']);

        $this->expectException(\DomainException::class);
        $service->issue($campaign, $prize, $admin);
    }

    public function test_verify_detects_direct_changes_to_domain_state_and_irreversible_fields(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        $service = app(PromotionWinService::class);
        $audit = app(PromotionAuditChain::class);
        $participant = User::factory()->create(['role' => 'guest', 'status' => true]);
        $issued = $service->issue($campaign, $prize, $admin);
        $participation = $service->bindToken($issued->plainToken, $participant);
        $service->confirmParticipation($participation, $participant);
        $win = $issued->win->fresh();
        $otherCampaign = PromotionCampaign::query()->create([
            'name' => 'Andere Kampagne',
            'code' => 'ALT'.fake()->unique()->numerify('#####'),
            'is_active' => true,
        ]);
        $otherPrize = PromotionPrize::query()->create([
            'campaign_id' => $otherCampaign->id,
            'code' => 'ALT',
            'name' => 'Anderer Gewinn',
            'fulfillment_mode' => 'onsite_staff',
            'quota' => 10,
            'is_active' => true,
        ]);

        $this->assertTrue($audit->verify($campaign));

        $issuedEvent = \App\Models\PromotionWinEvent::query()->where('event_type', 'win.issued')->firstOrFail();
        $boundEvent = \App\Models\PromotionWinEvent::query()->where('event_type', 'win.bound')->firstOrFail();
        $confirmedEvent = \App\Models\PromotionWinEvent::query()->where('event_type', 'win.confirmed')->firstOrFail();
        $this->assertNull(data_get($issuedEvent->payload, 'win_state.claim_key_digest'));
        $this->assertNotNull(data_get($boundEvent->payload, 'win_state.claim_key_digest'));
        $this->assertSame(
            data_get($boundEvent->payload, 'win_state.claim_key_digest'),
            data_get($confirmedEvent->payload, 'win_state.claim_key_digest'),
        );
        $this->assertSame(
            data_get($boundEvent->payload, 'participation_state'),
            data_get($confirmedEvent->payload, 'participation_state'),
        );

        $mutations = [
            'campaign_id' => $otherCampaign->id,
            'prize_id' => $otherPrize->id,
            'participation_id' => null,
            'status' => 'disputed',
            'issued_by' => $participant->id,
            'fulfilled_by' => $admin->id,
            'prize_name_snapshot' => 'Manipulierter Gewinn',
            'fulfillment_mode_snapshot' => 'onsite_staff',
            'token_hash' => str_repeat('a', 64),
            'claim_key' => str_repeat('b', 64),
            'bound_at' => now()->subDay()->format('Y-m-d H:i:s'),
            'confirmed_at' => null,
            'disputed_at' => now()->format('Y-m-d H:i:s'),
            'fulfilled_at' => now()->format('Y-m-d H:i:s'),
            'cancelled_at' => now()->format('Y-m-d H:i:s'),
        ];

        foreach ($mutations as $column => $tamperedValue) {
            $original = $win->getRawOriginal($column);
            DB::table('wins')->where('id', $win->id)->update([$column => $tamperedValue]);
            $this->assertFalse($audit->verify($campaign), "Direkte Manipulation von {$column} blieb unentdeckt.");
            DB::table('wins')->where('id', $win->id)->update([$column => $original]);
            $this->assertTrue($audit->verify($campaign), "Der Originalzustand von {$column} konnte nicht wieder verifiziert werden.");
        }

        $originalPublicId = $participation->public_id;
        DB::table('participations')->where('id', $participation->id)->update(['public_id' => 'RC-TAMPERED-2345-6789-X']);
        $this->assertFalse($audit->verify($campaign));
        DB::table('participations')->where('id', $participation->id)->update(['public_id' => $originalPublicId]);
        $this->assertTrue($audit->verify($campaign));

        DB::table('participations')->where('id', $participation->id)->update(['user_id' => $admin->id]);
        $this->assertFalse($audit->verify($campaign));
        DB::table('participations')->where('id', $participation->id)->update(['user_id' => $participant->id]);
        $this->assertTrue($audit->verify($campaign));
    }

    public function test_issue_rejects_direct_configuration_tampering_before_counter_or_win_write(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        DB::table('prizes')->where('id', $prize->id)->update(['quota' => 99]);

        try {
            app(PromotionWinService::class)->issue($campaign, $prize, $admin);
            $this->fail('Ein Gewinn wurde trotz unauditierter Kontingentmanipulation ausgegeben.');
        } catch (\DomainException $exception) {
            $this->assertStringContainsString('Auditkette', $exception->getMessage());
        }

        $this->assertSame(0, PromotionWin::query()->count());
        $this->assertSame(0, $prize->fresh()->reserved_count);
    }

    public function test_synchronous_verifier_detects_campaign_configuration_tampering(): void
    {
        [$campaign] = $this->promotion();
        $audit = app(PromotionAuditChain::class);

        $this->assertTrue($audit->verify($campaign));
        DB::table('campaigns')->where('id', $campaign->id)->update(['is_active' => false]);
        $this->assertFalse($audit->verify($campaign));
    }

    public function test_win_event_timestamps_are_transition_bound_and_remain_unchanged(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        $participant = User::factory()->create([
            'role' => 'guest',
            'status' => true,
            'email_verified_at' => now(),
        ]);
        $service = app(PromotionWinService::class);
        $issued = $service->issue($campaign, $prize, $admin);
        $participation = $service->bindToken($issued->plainToken, $participant);

        $boundState = PromotionWinEvent::query()->where('event_type', 'win.bound')->firstOrFail()->payload['win_state'];
        $this->assertNotNull($boundState['consumed_at']);
        $this->assertNotNull($boundState['bound_at']);
        $this->assertNull($boundState['confirmed_at']);
        $this->assertNull($boundState['fulfilled_at']);

        $service->confirmParticipation($participation, $participant);
        $confirmedState = PromotionWinEvent::query()->where('event_type', 'win.confirmed')->firstOrFail()->payload['win_state'];
        $this->assertSame($boundState['consumed_at'], $confirmedState['consumed_at']);
        $this->assertSame($boundState['bound_at'], $confirmedState['bound_at']);
        $this->assertNotNull($confirmedState['confirmed_at']);
        $this->assertNull($confirmedState['fulfilled_at']);

        $service->fulfill($issued->win, $admin);
        $fulfilledState = PromotionWinEvent::query()->where('event_type', 'win.fulfilled')->firstOrFail()->payload['win_state'];
        $this->assertSame($boundState['bound_at'], $fulfilledState['bound_at']);
        $this->assertSame($confirmedState['confirmed_at'], $fulfilledState['confirmed_at']);
        $this->assertNotNull($fulfilledState['fulfilled_at']);
        $this->assertTrue(app(PromotionAuditChain::class)->verify($campaign));
    }

    public function test_tampered_bound_state_cannot_be_rebound_or_confirmed_and_tampered_confirmation_cannot_be_fulfilled(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        $participant = User::factory()->create([
            'role' => 'guest',
            'status' => true,
            'email_verified_at' => now(),
        ]);
        $service = app(PromotionWinService::class);
        $issued = $service->issue($campaign, $prize, $admin);
        $participation = $service->bindToken($issued->plainToken, $participant);
        $boundWin = $issued->win->fresh();

        DB::table('wins')->where('id', $boundWin->id)->update([
            'participation_id' => null,
            'claim_key' => null,
            'status' => 'issued',
            'consumed_at' => null,
            'bound_at' => null,
        ]);

        try {
            $service->bindToken($issued->plainToken, $participant);
            $this->fail('Ein manipuliert zurueckgesetzter Token wurde erneut gebunden.');
        } catch (\DomainException $exception) {
            $this->assertStringContainsString('Auditkette', $exception->getMessage());
        }

        DB::table('wins')->where('id', $boundWin->id)->update([
            'participation_id' => $participation->id,
            'claim_key' => $boundWin->getRawOriginal('claim_key'),
            'status' => 'bound',
            'consumed_at' => $boundWin->getRawOriginal('consumed_at'),
            'bound_at' => $boundWin->getRawOriginal('bound_at'),
        ]);

        DB::table('wins')->where('id', $boundWin->id)->update([
            'bound_at' => now()->subDay()->format('Y-m-d H:i:s'),
        ]);
        try {
            $service->confirmParticipation($participation, $participant);
            $this->fail('Ein manipulierter Bindungszeitpunkt wurde vor der Bestaetigung akzeptiert.');
        } catch (\DomainException $exception) {
            $this->assertStringContainsString('Auditkette', $exception->getMessage());
        }
        $this->assertSame(0, PromotionWinEvent::query()->where('event_type', 'win.confirmed')->count());

        DB::table('wins')->where('id', $boundWin->id)->update([
            'bound_at' => $boundWin->getRawOriginal('bound_at'),
        ]);
        $service->confirmParticipation($participation, $participant);
        DB::table('wins')->where('id', $boundWin->id)->update([
            'confirmed_at' => now()->subDay()->format('Y-m-d H:i:s'),
        ]);

        try {
            $service->fulfill($boundWin, $admin);
            $this->fail('Ein manipulierter Bestaetigungszeitpunkt wurde vor der Ausgabe akzeptiert.');
        } catch (\DomainException $exception) {
            $this->assertStringContainsString('Auditkette', $exception->getMessage());
        }
        $this->assertSame(0, PromotionWinEvent::query()->where('event_type', 'win.fulfilled')->count());
    }

    /** @return array{PromotionCampaign, PromotionPrize, User} */
    private function promotion(): array
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => true]);
        $campaign = PromotionCampaign::query()->create([
            'name' => 'Strassenpromotion 2026',
            'code' => 'STR'.fake()->unique()->numerify('#####'),
            'is_active' => true,
        ]);
        $prize = PromotionPrize::query()->create([
            'campaign_id' => $campaign->id,
            'code' => 'AMZ20',
            'name' => 'Amazon-Gutschein 20 Euro',
            'fulfillment_mode' => 'external_admin',
            'quota' => 10,
            'is_active' => true,
        ]);

        $this->appendConfigurationBaseline($campaign, $admin);

        return [$campaign, $prize, $admin];
    }

    private function appendConfigurationBaseline(PromotionCampaign $campaign, User $actor): void
    {
        app(PromotionAuditChain::class)->appendConfiguration($campaign, 'campaign.configured', [
            'campaign' => [
                'id' => (int) $campaign->id,
                'code' => (string) $campaign->code,
                'name_digest' => hash('sha256', (string) $campaign->name),
                'starts_at' => $campaign->getRawOriginal('starts_at'),
                'ends_at' => $campaign->getRawOriginal('ends_at'),
                'is_active' => (bool) $campaign->is_active,
            ],
            'prizes' => $campaign->prizes()->orderBy('id')->get()->map(static fn (PromotionPrize $prize): array => [
                'id' => (int) $prize->id,
                'code' => (string) $prize->code,
                'name_digest' => hash('sha256', (string) $prize->name),
                'fulfillment_mode' => (string) $prize->getRawOriginal('fulfillment_mode'),
                'quota' => (int) $prize->quota,
                'reserved_count' => (int) $prize->reserved_count,
                'is_active' => (bool) $prize->is_active,
                'sort_order' => (int) $prize->sort_order,
                'configuration_digest' => hash('sha256', json_encode($prize->configuration, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES)),
            ])->all(),
        ], $actor);
    }
}

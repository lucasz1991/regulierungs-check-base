<?php

namespace Tests\Feature\Promotion;

use App\Enums\PromotionWinStatus;
use App\Models\PromotionCampaign;
use App\Models\PromotionPrize;
use App\Models\PromotionWin;
use App\Models\PromotionWinEvent;
use App\Models\User;
use App\Services\Promotion\PromotionAuditChain;
use App\Services\Promotion\PromotionSettingsService;
use App\Services\Promotion\PromotionWinService;
use App\Support\Promotion\ParticipationId;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use LogicException;
use Tests\TestCase;

class PromotionDomainSecurityTest extends TestCase
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

    public function test_quota_token_and_one_win_per_campaign_are_enforced(): void
    {
        [$campaign, $prize, $admin] = $this->promotion(quota: 1);
        $service = app(PromotionWinService::class);
        $issued = $service->issue($campaign, $prize, $admin);

        $this->assertSame(43, strlen($issued->plainToken));
        $this->assertNotSame($issued->plainToken, $issued->win->token_hash);
        $this->assertSame(1, $prize->fresh()->reserved_count);

        $this->expectException(DomainException::class);
        $service->issue($campaign, $prize, $admin);
    }

    public function test_token_is_single_use_and_second_win_for_same_account_is_rejected(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        $service = app(PromotionWinService::class);
        $user = User::factory()->create(['role' => 'guest']);
        $first = $service->issue($campaign, $prize, $admin);
        $participation = $service->bindToken($first->plainToken, $user);

        $this->assertTrue(ParticipationId::isValid($participation->public_id));

        try {
            $service->bindToken($first->plainToken, User::factory()->create(['role' => 'guest']));
            $this->fail('Ein verwendeter QR-Code wurde ein zweites Mal gebunden.');
        } catch (DomainException) {
            $this->assertTrue(true);
        }

        $second = $service->issue($campaign, $prize, $admin);

        $this->expectException(DomainException::class);
        $service->bindToken($second->plainToken, $user);
    }

    public function test_confirmation_and_fulfillment_are_idempotent_but_require_verified_email(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        $service = app(PromotionWinService::class);
        $user = User::factory()->unverified()->create(['role' => 'guest']);
        $issued = $service->issue($campaign, $prize, $admin);
        $participation = $service->bindToken($issued->plainToken, $user);

        $service->confirmParticipation($participation, $user);
        $service->confirmParticipation($participation, $user);

        try {
            $service->fulfill($issued->win, $admin);
            $this->fail('Ein Gewinn wurde vor der E-Mail-Verifikation ausgegeben.');
        } catch (DomainException) {
            $this->assertTrue(true);
        }

        $user->forceFill(['email_verified_at' => now()])->save();
        $fulfilled = $service->fulfill($issued->win, $admin);
        $again = $service->fulfill($fulfilled, $admin);

        $this->assertSame(PromotionWinStatus::Fulfilled, $again->status);
        $this->assertSame(1, PromotionWinEvent::query()->where('event_type', 'win.fulfilled')->count());
    }

    public function test_expiry_keeps_reservation_until_admin_cancellation(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        $service = app(PromotionWinService::class);
        $issued = $service->issue($campaign, $prize, $admin);
        $this->travel(app(PromotionSettingsService::class)->qrTtlMinutes() + 1)->minutes();

        $expired = $service->expire($issued->win);
        $this->assertSame(PromotionWinStatus::Expired, $expired->status);
        $this->assertSame(1, $prize->fresh()->reserved_count);

        $cancelled = $service->cancel($expired, $admin, 'expired_reservation_released');
        $this->assertSame(PromotionWinStatus::Cancelled, $cancelled->status);
        $this->assertSame(0, $prize->fresh()->reserved_count);
    }

    public function test_audit_chain_is_contiguous_immutable_and_detects_database_tampering(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        $service = app(PromotionWinService::class);
        $user = User::factory()->create(['role' => 'guest']);
        $issued = $service->issue($campaign, $prize, $admin, [
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PromotionTest/1.0',
        ]);
        $participation = $service->bindToken($issued->plainToken, $user);
        $service->confirmParticipation($participation, $user);

        $this->assertTrue(app(PromotionAuditChain::class)->verify($campaign));
        $this->assertSame([1, 2, 3, 4], PromotionWinEvent::query()->orderBy('sequence')->pluck('sequence')->all());
        $this->assertDatabaseMissing('win_events', ['actor_ref' => $user->email]);
        $this->assertFalse(Schema::hasTable('promotion_access_contexts'));

        $this->expectException(LogicException::class);
        PromotionWinEvent::query()->firstOrFail()->update(['event_type' => 'tampered']);
    }

    public function test_direct_sql_tampering_invalidates_chain(): void
    {
        [$campaign, $prize, $admin] = $this->promotion();
        app(PromotionWinService::class)->issue($campaign, $prize, $admin);
        DB::table('win_events')->where('campaign_id', $campaign->id)->update(['payload' => '{"tampered":true}']);

        $this->assertFalse(app(PromotionAuditChain::class)->verify($campaign));
    }

    public function test_promotion_has_no_console_or_background_maintenance_contract(): void
    {
        $commands = Artisan::all();

        $this->assertArrayNotHasKey('promotion:expire-wins', $commands);
        $this->assertArrayNotHasKey('promotion:audit-anchor', $commands);
        $this->assertArrayNotHasKey('promotion:purge-access-contexts', $commands);
        $this->assertFalse(Schema::hasTable('promotion_access_contexts'));
    }

    /** @return array{PromotionCampaign, PromotionPrize, User} */
    private function promotion(int $quota = 10): array
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => true]);
        $campaign = PromotionCampaign::query()->create([
            'name' => 'Straßenpromotion 2026',
            'code' => 'STR'.fake()->unique()->numerify('#####'),
            'is_active' => true,
        ]);
        $prize = PromotionPrize::query()->create([
            'campaign_id' => $campaign->id,
            'code' => 'AMZ20',
            'name' => 'Amazon-Gutschein 20 €',
            'fulfillment_mode' => 'external_admin',
            'quota' => $quota,
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
        ], $admin);

        return [$campaign, $prize, $admin];
    }
}

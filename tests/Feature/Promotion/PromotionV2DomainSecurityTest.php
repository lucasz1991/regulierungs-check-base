<?php

namespace Tests\Feature\Promotion;

use App\Enums\PromotionMailStatus;
use App\Enums\PromotionOutcomeType;
use App\Enums\PromotionTicketStatus;
use App\Enums\PromotionTurnStatus;
use App\Models\PromotionCampaign;
use App\Models\PromotionCampaignState;
use App\Models\PromotionPrize;
use App\Models\PromotionTicket;
use App\Models\PromotionTurn;
use App\Models\User;
use App\Services\Promotion\PromotionAuditChain;
use App\Services\Promotion\PromotionSettingsService;
use App\Services\Promotion\PromotionTicketQrSigner;
use App\Services\Promotion\PromotionTicketService;
use App\Services\Promotion\PromotionTurnService;
use App\Services\Promotion\PromotionWinService;
use App\Support\Promotion\ParticipationId;
use DomainException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Support\CreatesPromotionParticipants;
use Tests\TestCase;

class PromotionV2DomainSecurityTest extends TestCase
{
    use CreatesPromotionParticipants;
    use RefreshDatabase;

    private PromotionCampaign $campaign;

    private PromotionPrize $prize;

    private PromotionPrize $noWin;

    private PromotionPrize $retry;

    private User $admin;

    private PromotionTicketService $tickets;

    private PromotionTurnService $turns;

    private PromotionAuditChain $audit;

    protected function setUp(): void
    {
        parent::setUp();

        self::assertSame('sqlite', DB::getDriverName());
        self::assertSame(':memory:', config('database.connections.sqlite.database'));

        app(PromotionSettingsService::class)->save([
            'enabled' => true,
            'redemption_base_url' => 'https://promotion.example.test',
            'qr_ttl_minutes' => 30,
        ]);

        $this->admin = User::factory()->create(['role' => 'admin', 'status' => true]);
        $this->tickets = app(PromotionTicketService::class);
        $this->turns = app(PromotionTurnService::class);
        $this->audit = app(PromotionAuditChain::class);

        [$this->campaign, $this->prize, $this->noWin, $this->retry] = $this->createAndPublishCampaign('block', 'V2A');
    }

    public function test_one_active_turn_is_enforced_and_retry_then_final_result_is_atomic(): void
    {
        $first = $this->ticket();
        $second = $this->ticket();

        $turn = $this->turns->scanTicket($first->participation->public_id, $this->admin);

        self::assertSame(PromotionTurnStatus::Active, $turn->status);
        self::assertSame(PromotionTicketStatus::Active, $first->fresh()->status);
        self::assertSame($turn->id, $this->campaign->promotionState()->value('active_turn_id'));

        $this->assertDomainRejected(
            fn () => $this->turns->scanTicket(app(PromotionTicketQrSigner::class)->payload($second), $this->admin),
            'bereits ein anderer Teilnehmer aktiv',
        );
        self::assertSame(1, PromotionTurn::query()->where('campaign_id', $this->campaign->id)->where('status', 'active')->count());

        $retry = $this->turns->recordResult($turn, $this->retry, PromotionOutcomeType::Retry, $this->admin);
        self::assertFalse($retry->is_final);
        self::assertSame(PromotionMailStatus::NotRequired, $retry->mail_status);
        self::assertSame(PromotionTurnStatus::Active, $turn->fresh()->status);

        $final = $this->turns->recordResult($turn, $this->noWin, PromotionOutcomeType::NoWin, $this->admin);
        self::assertTrue($final->is_final);
        self::assertSame(PromotionMailStatus::Pending, $final->mail_status);
        self::assertSame(PromotionTurnStatus::Completed, $turn->fresh()->status);
        self::assertSame(PromotionTicketStatus::Completed, $first->fresh()->status);
        self::assertNull($this->campaign->promotionState()->value('active_turn_id'));
        self::assertTrue($this->audit->verify($this->campaign));

        $this->assertDomainRejected(fn () => $this->turns->scanTicket($first->participation->public_id, $this->admin));
    }

    public function test_releasing_a_turn_restores_the_same_ticket_without_creating_a_second_ticket(): void
    {
        $ticket = $this->ticket();
        $turn = $this->turns->scanTicket(app(PromotionTicketQrSigner::class)->payload($ticket), $this->admin);

        $released = $this->turns->releaseTurn($turn, $this->admin, 'Teilnehmer kurz abwesend');

        self::assertSame(PromotionTurnStatus::Released, $released->status);
        self::assertSame(PromotionTicketStatus::Ready, $ticket->fresh()->status);
        self::assertNull($this->campaign->promotionState()->value('active_turn_id'));
        self::assertTrue($this->tickets->ensureTicket($ticket->user, $this->campaign)->is($ticket));
        self::assertSame(1, PromotionTicket::query()->where('user_id', $ticket->user_id)->count());

        $next = $this->turns->scanTicket($ticket->participation->public_id, $this->admin);
        self::assertNotSame($turn->id, $next->id);
        self::assertTrue($this->audit->verify($this->campaign));
    }

    public function test_scan_rechecks_that_the_ticket_owner_is_active_and_email_verified(): void
    {
        $disabled = $this->ticket();
        $disabled->user->forceFill(['status' => false])->save();

        $this->assertDomainRejected(
            fn () => $this->turns->scanTicket(app(PromotionTicketQrSigner::class)->payload($disabled), $this->admin),
            'Teilnehmerkonto',
        );

        $unverified = $this->ticket();
        $unverified->user->forceFill(['email_verified_at' => null])->save();

        $this->assertDomainRejected(
            fn () => $this->turns->scanTicket($unverified->participation->public_id, $this->admin),
            'Teilnehmerkonto',
        );

        $promoted = $this->ticket();
        $promoted->user->forceFill(['role' => 'staff'])->save();
        $this->assertDomainRejected(
            fn () => $this->turns->scanTicket($promoted->participation->public_id, $this->admin),
            'Teilnehmerkonto',
        );

        self::assertSame(PromotionTicketStatus::Ready, $disabled->fresh()->status);
        self::assertSame(PromotionTicketStatus::Ready, $unverified->fresh()->status);
        self::assertSame(PromotionTicketStatus::Ready, $promoted->fresh()->status);
        self::assertSame(0, PromotionTurn::query()->where('campaign_id', $this->campaign->id)->count());
        self::assertNull($this->campaign->promotionState()->value('active_turn_id'));
        self::assertTrue($this->audit->verify($this->campaign));
    }

    public function test_ticket_creation_rejects_privileged_and_incomplete_accounts(): void
    {
        $rejectedUserIds = [];
        foreach (['admin', 'staff'] as $role) {
            $privileged = $this->createPromotionParticipant();
            $privileged->forceFill(['role' => $role])->save();
            $rejectedUserIds[] = $privileged->getKey();

            $this->assertDomainRejected(
                fn () => $this->tickets->ensureTicket($privileged, $this->campaign),
                'regulaeres Teilnehmerkonto',
            );
        }

        $incomplete = User::factory()->create(['role' => 'guest', 'status' => true]);
        $rejectedUserIds[] = $incomplete->getKey();
        $this->assertDomainRejected(
            fn () => $this->tickets->ensureTicket($incomplete, $this->campaign),
            'vollstaendig eingerichtet',
        );

        self::assertFalse(PromotionTicket::query()->whereIn('user_id', $rejectedUserIds)->exists());
    }

    public function test_block_policy_stops_the_next_scan_after_quota_is_consumed(): void
    {
        $turn = $this->turns->scanTicket($this->ticket()->participation->public_id, $this->admin);
        $result = $this->turns->recordResult($turn, $this->prize, PromotionOutcomeType::Prize, $this->admin);

        self::assertSame(PromotionOutcomeType::Prize, $result->outcome_type_snapshot);
        self::assertSame(1, $this->prize->fresh()->awarded_count);

        $waiting = $this->ticket();
        $this->assertDomainRejected(
            fn () => $this->turns->scanTicket($waiting->participation->public_id, $this->admin),
            'Kontingent',
        );
        self::assertSame(PromotionTicketStatus::Ready, $waiting->fresh()->status);
        self::assertTrue($this->audit->verify($this->campaign));
    }

    public function test_sticker_policy_requires_acknowledgement_and_records_exhausted_field_as_reroll(): void
    {
        [$campaign, $prize, $noWin] = $this->createAndPublishCampaign('sticker_continue', 'V2S');

        $firstTurn = $this->turns->scanTicket($this->ticket($campaign)->participation->public_id, $this->admin);
        $this->turns->recordResult($firstTurn, $prize, PromotionOutcomeType::Prize, $this->admin);
        self::assertTrue((bool) $campaign->promotionState()->value('sticker_required'));

        $second = $this->ticket($campaign);
        $this->assertDomainRejected(
            fn () => $this->turns->scanTicket($second->participation->public_id, $this->admin),
            'Abkleben',
        );

        $state = $this->turns->acknowledgeSticker($campaign, $this->admin);
        self::assertFalse($state->sticker_required);

        $secondTurn = $this->turns->scanTicket($second->participation->public_id, $this->admin);
        $reroll = $this->turns->recordResult($secondTurn, $prize, PromotionOutcomeType::Prize, $this->admin);
        self::assertSame(PromotionOutcomeType::QuotaReroll, $reroll->outcome_type_snapshot);
        self::assertFalse($reroll->is_final);
        self::assertSame(PromotionMailStatus::NotRequired, $reroll->mail_status);
        self::assertSame(PromotionTurnStatus::Active, $secondTurn->fresh()->status);
        self::assertTrue((bool) $campaign->promotionState()->value('sticker_required'));
        self::assertSame(1, $prize->fresh()->awarded_count);

        $final = $this->turns->recordResult($secondTurn, $noWin, PromotionOutcomeType::NoWin, $this->admin);
        self::assertTrue($final->is_final);
        self::assertTrue($this->audit->verify($campaign));
    }

    public function test_runtime_initialization_requires_sticker_for_a_legacy_exhausted_field(): void
    {
        [$campaign, $prize] = $this->createAndPublishCampaign('sticker_continue', 'V2LEGACY');
        app(PromotionWinService::class)->issue($campaign, $prize, $this->admin);
        self::assertFalse(PromotionCampaignState::query()->whereKey($campaign->id)->exists());

        $ticket = $this->tickets->ensureTicket($this->createPromotionParticipant(), $campaign);
        $state = PromotionCampaignState::query()->findOrFail($campaign->id);

        self::assertSame(PromotionTicketStatus::Ready, $ticket->status);
        self::assertTrue($state->sticker_required);
        self::assertNull($state->sticker_acknowledged_at);
        self::assertNull($state->sticker_acknowledged_by);
        self::assertSame(1, $prize->fresh()->reserved_count);
        self::assertSame(1, $prize->fresh()->awarded_count);
        self::assertTrue($this->audit->verify($campaign));
        $this->assertDomainRejected(
            fn () => $this->turns->scanTicket($ticket->participation->public_id, $this->admin),
            'Abkleben',
        );
    }

    public function test_staff_can_correct_repeatedly_inside_original_window_and_fulfillment_closes_it(): void
    {
        $this->prize->update(['quota' => 2]);
        $this->audit->appendConfiguration(
            $this->campaign,
            'prize.configured',
            ['prize' => $this->audit->configurationPayload($this->campaign)['prizes'][0]],
            $this->admin,
        );

        $turn = $this->turns->scanTicket($this->ticket()->participation->public_id, $this->admin);
        $original = $this->turns->recordResult($turn, $this->prize, PromotionOutcomeType::Prize, $this->admin);
        $toNoWin = $this->turns->correctResult($original, $this->noWin, PromotionOutcomeType::NoWin, $this->admin, 'Falsches Feld');

        self::assertNotNull($original->fresh()->superseded_at);
        self::assertSame(0, $this->prize->fresh()->awarded_count);
        self::assertSame($original->id, $toNoWin->corrects_result_id);

        $backToPrize = $this->turns->correctResult($toNoWin, $this->prize, PromotionOutcomeType::Prize, $this->admin, 'Korrektur geprueft');
        self::assertSame(1, $this->prize->fresh()->awarded_count);
        self::assertSame($toNoWin->id, $backToPrize->corrects_result_id);

        $fulfilled = $this->turns->fulfill($backToPrize, $this->admin);
        self::assertNotNull($fulfilled->fulfilled_at);

        $unauthorized = User::factory()->create(['role' => 'guest', 'status' => true]);
        $this->assertDomainRejected(fn () => $this->turns->fulfill($fulfilled, $unauthorized), 'Berechtigung');
        $this->assertDomainRejected(
            fn () => $this->turns->correctResult($fulfilled, $this->noWin, PromotionOutcomeType::NoWin, $this->admin, 'Zu spaet'),
            'nicht mehr korrigierbar',
        );
        self::assertTrue($this->audit->verify($this->campaign));
    }

    public function test_correction_window_never_extends_and_only_admin_counterbooking_can_follow_it(): void
    {
        $turn = $this->turns->scanTicket($this->ticket()->participation->public_id, $this->admin);
        $original = $this->turns->recordResult($turn, $this->noWin, PromotionOutcomeType::NoWin, $this->admin);
        $originalCompletedAt = $turn->fresh()->completed_at;
        $otherAdmin = User::factory()->create(['role' => 'admin', 'status' => true]);

        $this->assertDomainRejected(
            fn () => $this->turns->correctResult($original, $this->prize, PromotionOutcomeType::Prize, $otherAdmin, 'Fremde Korrektur'),
            'erfassende Mitarbeiter',
        );

        $this->travel(9)->minutes();
        $corrected = $this->turns->correctResult($original, $this->prize, PromotionOutcomeType::Prize, $this->admin, 'Innerhalb der Frist');
        self::assertTrue($originalCompletedAt->equalTo($turn->fresh()->completed_at));

        $this->travel(2)->minutes();
        $this->assertDomainRejected(
            fn () => $this->turns->correctResult($corrected, $this->noWin, PromotionOutcomeType::NoWin, $this->admin, 'Soll nicht verlaengern'),
            'Korrekturfrist',
        );
        $this->assertDomainRejected(
            fn () => $this->turns->counterbookResult($corrected, $this->noWin, PromotionOutcomeType::NoWin, $otherAdmin, ''),
            'gueltigen Grund',
        );

        $counterbooked = $this->turns->counterbookResult(
            $corrected,
            $this->noWin,
            PromotionOutcomeType::NoWin,
            $otherAdmin,
            'Volladmin-Gegenbuchung nach Kontrollanruf',
        );
        self::assertSame($corrected->id, $counterbooked->corrects_result_id);
        self::assertSame(PromotionOutcomeType::NoWin, $counterbooked->outcome_type_snapshot);
        self::assertSame(0, $this->prize->fresh()->awarded_count);
        self::assertTrue($this->audit->verify($this->campaign));
    }

    public function test_turn_starter_cannot_correct_a_result_recorded_by_another_actor(): void
    {
        $ticket = $this->ticket();
        $turn = $this->turns->scanTicket($ticket->participation->public_id, $this->admin);
        $recordingAdmin = User::factory()->create(['role' => 'admin', 'status' => true]);
        $result = $this->turns->recordResult($turn, $this->noWin, PromotionOutcomeType::NoWin, $recordingAdmin);

        self::assertSame($this->admin->id, $turn->started_by);
        self::assertSame($recordingAdmin->id, $result->recorded_by);
        $this->assertDomainRejected(
            fn () => $this->turns->correctResult($result, $this->prize, PromotionOutcomeType::Prize, $this->admin, 'Nicht selbst erfasst'),
            'aktuelle Ergebnis erfasst',
        );

        $counterbooked = $this->turns->counterbookResult(
            $result,
            $this->prize,
            PromotionOutcomeType::Prize,
            $this->admin,
            'Volladmin-Gegenbuchung mit Pflichtgrund',
        );
        self::assertSame($result->id, $counterbooked->corrects_result_id);
        self::assertTrue($this->audit->verify($this->campaign));
    }

    public function test_database_constraints_back_ticket_uniqueness_and_campaign_state_singleton(): void
    {
        $ticket = $this->ticket();

        $this->assertQueryConstraintRejected(fn () => PromotionTicket::query()->create([
            'participation_id' => $ticket->participation_id,
            'campaign_id' => $ticket->campaign_id,
            'user_id' => $ticket->user_id,
            'status' => PromotionTicketStatus::Ready,
            'issued_at' => now(),
        ]));
        $this->assertQueryConstraintRejected(fn () => PromotionCampaignState::query()->create([
            'campaign_id' => $this->campaign->id,
            'active_turn_id' => null,
            'sticker_required' => false,
        ]));

        self::assertSame(1, PromotionTicket::query()->where('participation_id', $ticket->participation_id)->count());
        self::assertSame(1, PromotionCampaignState::query()->where('campaign_id', $this->campaign->id)->count());
    }

    public function test_public_slot_can_be_switched_back_to_a_lower_campaign_id_atomically(): void
    {
        [$newerCampaign] = $this->createAndPublishCampaign('block', 'V2B');
        self::assertTrue($newerCampaign->fresh()->is_public);

        $published = $this->tickets->publishCampaign($this->campaign, $this->admin);

        self::assertTrue($published->is_public);
        self::assertSame(1, $published->public_slot);
        self::assertFalse($newerCampaign->fresh()->is_public);
        self::assertNull($newerCampaign->fresh()->public_slot);
        self::assertSame($this->campaign->id, app(PromotionSettingsService::class)->publicCampaignId());
        self::assertTrue($this->audit->verify($this->campaign));
        self::assertTrue($this->audit->verify($newerCampaign));
    }

    public function test_public_campaign_cannot_be_switched_or_unpublished_during_an_active_turn(): void
    {
        [$turn] = $this->activeTurn();
        [$nextCampaign] = $this->createAndPublishCampaign('block', 'V2C', false);

        $this->assertDomainRejected(
            fn () => $this->tickets->publishCampaign(null, $this->admin),
            'aktiven Gluecksrad-Drehung',
        );
        $this->assertDomainRejected(
            fn () => $this->tickets->publishCampaign($nextCampaign, $this->admin),
            'aktiven Gluecksrad-Drehung',
        );

        self::assertSame($this->campaign->id, app(PromotionSettingsService::class)->publicCampaignId());
        self::assertTrue($this->campaign->fresh()->is_public);
        self::assertFalse($nextCampaign->fresh()->is_public);
        self::assertSame(PromotionTurnStatus::Active, $turn->fresh()->status);

        $this->turns->releaseTurn($turn, $this->admin, 'Kampagnenwechsel vorbereitet');
        self::assertTrue($this->tickets->publishCampaign($nextCampaign, $this->admin)->is_public);
    }

    public function test_mail_state_machine_only_allows_audited_pending_failed_retry_and_sent_transitions(): void
    {
        $turn = $this->turns->scanTicket($this->ticket()->participation->public_id, $this->admin);
        $result = $this->turns->recordResult($turn, $this->noWin, PromotionOutcomeType::NoWin, $this->admin);

        $failed = $this->turns->markMailFailed($result, 'SMTP unavailable');
        self::assertSame(PromotionMailStatus::Failed, $failed->mail_status);
        self::assertMatchesRegularExpression('/\A[a-f0-9]{64}\z/', (string) $failed->mail_error_digest);

        $normalUser = User::factory()->create(['role' => 'guest', 'status' => true]);
        $this->assertDomainRejected(fn () => $this->turns->markMailPendingForResend($failed, $normalUser), 'Volladmin');

        $pendingAgain = $this->turns->markMailPendingForResend($failed, $this->admin);
        self::assertSame(PromotionMailStatus::Pending, $pendingAgain->mail_status);
        self::assertNull($pendingAgain->mail_error_digest);

        $sent = $this->turns->markMailSent($pendingAgain);
        self::assertSame(PromotionMailStatus::Sent, $sent->mail_status);
        self::assertNotNull($sent->mail_sent_at);
        $this->assertDomainRejected(fn () => $this->turns->markMailFailed($sent, 'late failure'), 'ausstehende');
        self::assertTrue($this->audit->verify($this->campaign));
    }

    public function test_ticket_tampering_blocks_the_next_legitimate_transition(): void
    {
        [$turn, $ticket] = $this->activeTurn();
        DB::table('promotion_tickets')->where('id', $ticket->id)->update(['activated_at' => now()->subDay()]);

        $this->assertAuditRejected(fn () => $this->turns->releaseTurn($turn, $this->admin, 'Abbruch'));
    }

    public function test_participation_identity_and_ticket_links_are_audit_bound(): void
    {
        $ticket = $this->ticket();
        $participation = $ticket->participation;
        $original = [
            'campaign_id' => $participation->campaign_id,
            'user_id' => $participation->user_id,
            'public_id' => $participation->public_id,
        ];
        [$otherCampaign] = $this->createAndPublishCampaign('block', 'V2LINK', false);
        $otherUser = User::factory()->create(['role' => 'guest', 'status' => true]);

        foreach ([
            ['public_id' => ParticipationId::generate((string) $this->campaign->code)],
            ['user_id' => $otherUser->getKey()],
            ['campaign_id' => $otherCampaign->getKey()],
        ] as $tamper) {
            DB::table('participations')->where('id', $participation->id)->update($tamper);

            self::assertFalse($this->audit->verify($this->campaign));
            $this->assertDomainRejected(
                fn () => app(PromotionTicketQrSigner::class)->payload($ticket),
                'Auditdaten',
            );
            $this->assertDomainRejected(
                fn () => $this->turns->scanTicket(
                    (string) DB::table('participations')->where('id', $participation->id)->value('public_id'),
                    $this->admin,
                ),
                'Auditdaten',
            );

            DB::table('participations')->where('id', $participation->id)->update($original);
            self::assertTrue($this->audit->verify($this->campaign));
        }
    }

    public function test_turn_tampering_blocks_the_next_legitimate_transition(): void
    {
        [$turn] = $this->activeTurn();
        DB::table('promotion_turns')->where('id', $turn->id)->update(['started_at' => now()->subDay()]);

        $this->assertAuditRejected(fn () => $this->turns->recordResult($turn, $this->retry, PromotionOutcomeType::Retry, $this->admin));
    }

    public function test_campaign_runtime_state_tampering_blocks_the_next_legitimate_transition(): void
    {
        [$turn] = $this->activeTurn();
        DB::table('promotion_campaign_states')->where('campaign_id', $this->campaign->id)->update(['sticker_required' => true]);

        $this->assertAuditRejected(fn () => $this->turns->recordResult($turn, $this->retry, PromotionOutcomeType::Retry, $this->admin));
    }

    public function test_deleted_runtime_state_cannot_open_a_second_turn(): void
    {
        $first = $this->ticket();
        $second = $this->ticket();
        self::assertDatabaseHas('promotion_campaign_states', [
            'campaign_id' => $this->campaign->id,
            'active_turn_id' => null,
        ]);
        self::assertDatabaseHas('win_events', [
            'campaign_id' => $this->campaign->id,
            'event_type' => 'campaign.runtime_initialized',
        ]);

        $firstTurn = $this->turns->scanTicket($first->participation->public_id, $this->admin);
        DB::table('promotion_campaign_states')->where('campaign_id', $this->campaign->id)->delete();

        self::assertFalse($this->audit->verify($this->campaign));
        $this->assertDomainRejected(
            fn () => $this->turns->scanTicket($second->participation->public_id, $this->admin),
            'Kampagnenzustand fehlt',
        );
        self::assertSame(PromotionTurnStatus::Active, $firstTurn->fresh()->status);
        self::assertSame(PromotionTicketStatus::Ready, $second->fresh()->status);
        self::assertSame(1, PromotionTurn::query()->where('campaign_id', $this->campaign->id)->where('status', 'active')->count());
    }

    public function test_result_and_mail_tampering_blocks_the_next_legitimate_transition(): void
    {
        [$turn] = $this->activeTurn();
        $result = $this->turns->recordResult($turn, $this->noWin, PromotionOutcomeType::NoWin, $this->admin);
        DB::table('promotion_spin_results')->where('id', $result->id)->update(['mail_error_digest' => str_repeat('a', 64)]);

        $this->assertAuditRejected(fn () => $this->turns->markMailSent($result));
    }

    public function test_hash_chain_tampering_blocks_a_new_scan(): void
    {
        $ticket = $this->ticket();
        DB::table('win_events')->where('campaign_id', $this->campaign->id)->orderByDesc('sequence')->limit(1)
            ->update(['event_hash' => str_repeat('f', 64)]);

        $this->assertAuditRejected(fn () => $this->turns->scanTicket($ticket->participation->public_id, $this->admin));
    }

    public function test_ticket_schema_contains_no_qr_file_or_bearer_token_storage(): void
    {
        $columns = Schema::getColumnListing('promotion_tickets');

        self::assertNotContains('token', $columns);
        self::assertNotContains('token_hash', $columns);
        self::assertNotContains('qr_path', $columns);
        self::assertNotContains('qr_svg', $columns);
    }

    public function test_non_cancelled_legacy_participation_is_imported_as_an_already_completed_ticket(): void
    {
        $participant = $this->createPromotionParticipant();
        $legacy = app(PromotionWinService::class)->issue($this->campaign, $this->prize, $this->admin);
        $participation = app(PromotionWinService::class)->bindToken($legacy->plainToken, $participant);

        $ticket = $this->tickets->ensureTicket($participant, $this->campaign);

        self::assertSame($participation->id, $ticket->participation_id);
        self::assertSame(PromotionTicketStatus::Completed, $ticket->status);
        self::assertNotNull($ticket->completed_at);
        self::assertTrue($this->audit->verify($this->campaign));
        $this->assertDomainRejected(fn () => $this->turns->scanTicket($participation->public_id, $this->admin));
    }

    /** @return array{PromotionCampaign, PromotionPrize, PromotionPrize, PromotionPrize} */
    private function createAndPublishCampaign(string $policy, string $code, bool $publish = true): array
    {
        $campaign = PromotionCampaign::query()->create([
            'code' => $code,
            'name' => 'Glücksrad '.$code,
            'landing_headline' => 'Dreh dein Glück',
            'landing_text' => 'Ticket holen und am Rad zeigen.',
            'rules_text' => 'Ein Ticket je Konto und Kampagne.',
            'quota_exhaustion_policy' => $policy,
            'is_active' => true,
            'created_by' => $this->admin->id,
        ]);
        $prize = PromotionPrize::query()->create([
            'campaign_id' => $campaign->id,
            'code' => 'GEWINN',
            'name' => 'Gewinn',
            'outcome_type' => PromotionOutcomeType::Prize,
            'fulfillment_mode' => 'onsite_staff',
            'quota' => 1,
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $noWin = PromotionPrize::query()->create([
            'campaign_id' => $campaign->id,
            'code' => 'NIETE',
            'name' => 'Niete',
            'outcome_type' => PromotionOutcomeType::NoWin,
            'fulfillment_mode' => 'onsite_staff',
            'quota' => 9999,
            'is_active' => true,
            'sort_order' => 2,
        ]);
        $retry = PromotionPrize::query()->create([
            'campaign_id' => $campaign->id,
            'code' => 'ZUSATZ',
            'name' => 'Zusatzdreh',
            'outcome_type' => PromotionOutcomeType::Retry,
            'fulfillment_mode' => 'onsite_staff',
            'quota' => 9999,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $this->audit->appendConfiguration(
            $campaign,
            'campaign.configured',
            $this->audit->configurationPayload($campaign),
            $this->admin,
        );
        if ($publish) {
            $this->tickets->publishCampaign($campaign, $this->admin);
        }

        return [$campaign->fresh(), $prize->fresh(), $noWin->fresh(), $retry->fresh()];
    }

    private function ticket(?PromotionCampaign $campaign = null): PromotionTicket
    {
        return $this->tickets->ensureTicket(
            $this->createPromotionParticipant(),
            $campaign ?? $this->campaign,
        );
    }

    /** @return array{PromotionTurn, PromotionTicket} */
    private function activeTurn(): array
    {
        $ticket = $this->ticket();
        $turn = $this->turns->scanTicket($ticket->participation->public_id, $this->admin);

        return [$turn, $ticket];
    }

    private function assertAuditRejected(callable $operation): void
    {
        $this->assertDomainRejected($operation, 'Auditdaten');
        self::assertFalse($this->audit->verify($this->campaign));
    }

    private function assertDomainRejected(callable $operation, string $messageContains = ''): void
    {
        try {
            $operation();
            self::fail('Die Domainoperation haette abgewiesen werden muessen.');
        } catch (DomainException $exception) {
            if ($messageContains !== '') {
                self::assertStringContainsStringIgnoringCase($messageContains, $exception->getMessage());
            }
        }
    }

    private function assertQueryConstraintRejected(callable $operation): void
    {
        try {
            $operation();
            self::fail('Die Datenbank-Constraint haette den doppelten Datensatz abweisen muessen.');
        } catch (QueryException) {
            $this->addToAssertionCount(1);
        }
    }
}

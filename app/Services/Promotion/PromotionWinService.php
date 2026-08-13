<?php

namespace App\Services\Promotion;

use App\Enums\PromotionFulfillmentMode;
use App\Enums\PromotionWinStatus;
use App\Models\PromotionCampaign;
use App\Models\PromotionParticipation;
use App\Models\PromotionPrize;
use App\Models\PromotionWin;
use App\Models\User;
use App\Support\Promotion\IssuedPromotionWin;
use App\Support\Promotion\ParticipationId;
use DomainException;
use Illuminate\Support\Facades\DB;

final class PromotionWinService
{
    public const CANCELLATION_REASONS = [
        'issued_in_error',
        'participant_dispute_upheld',
        'campaign_cancelled',
        'technical_duplicate',
        'expired_reservation_released',
    ];

    public function __construct(
        private readonly PromotionAuditChain $audit,
        private readonly PromotionSettingsService $settings,
    ) {
    }

    public function inspectToken(string $plainToken): PromotionWin
    {
        $this->assertEnabled();
        $win = PromotionWin::query()
            ->with(['campaign', 'prize'])
            ->where('token_hash', $this->tokenDigest($plainToken))
            ->first();

        if (! $win || $win->status !== PromotionWinStatus::Issued) {
            throw new DomainException('Der Einmal-Code ist ungueltig, abgelaufen oder bereits verwendet.');
        }

        if ($win->expires_at->isPast()) {
            $this->expire($win);

            throw new DomainException('Der Einmal-Code ist ungueltig, abgelaufen oder bereits verwendet.');
        }

        return $win;
    }

    public function issue(
        PromotionCampaign $campaign,
        PromotionPrize $prize,
        User $issuedBy,
        array $context = [],
    ): IssuedPromotionWin {
        $this->assertEnabled();
        $this->assertCanRecord($issuedBy);

        return DB::transaction(function () use ($campaign, $prize, $issuedBy, $context): IssuedPromotionWin {
            $issuedBy = User::query()->lockForUpdate()->findOrFail($issuedBy->getKey());
            $this->assertCanRecord($issuedBy);
            $campaign = PromotionCampaign::query()->lockForUpdate()->findOrFail($campaign->getKey());
            $prize = PromotionPrize::query()->lockForUpdate()->findOrFail($prize->getKey());

            if (! $campaign->isOpen() || ! $prize->is_active || (int) $prize->campaign_id !== (int) $campaign->getKey()) {
                throw new DomainException('Kampagne oder Gewinnart ist nicht aktiv.');
            }

            if (! $this->audit->verify($campaign)) {
                throw new DomainException('Die Promotion-Konfiguration oder Auditkette ist ungueltig; es wird kein Gewinn ausgegeben.');
            }

            if ($prize->reserved_count >= $prize->quota) {
                throw new DomainException('Das Kontingent dieser Gewinnart ist erschoepft.');
            }

            do {
                $plainToken = $this->newPlainToken();
                $tokenHash = hash('sha256', $plainToken);
            } while (PromotionWin::query()->where('token_hash', $tokenHash)->exists());

            $prize->increment('reserved_count');
            $win = PromotionWin::query()->create([
                'campaign_id' => $campaign->getKey(),
                'prize_id' => $prize->getKey(),
                'issued_by' => $issuedBy->getKey(),
                'status' => PromotionWinStatus::Issued,
                'token_hash' => $tokenHash,
                'prize_name_snapshot' => $prize->name,
                'fulfillment_mode_snapshot' => $prize->fulfillment_mode,
                'expires_at' => now()->addMinutes($this->settings->qrTtlMinutes()),
            ]);

            $this->audit->append($campaign, 'win.issued', $win, null, $issuedBy, [
                'prize_id' => $prize->getKey(),
                'fulfillment_mode' => $prize->fulfillment_mode->value,
                'status' => PromotionWinStatus::Issued->value,
            ], $context);

            return new IssuedPromotionWin($win->fresh(['campaign', 'prize']), $plainToken);
        }, 5);
    }

    public function bindToken(string $plainToken, User $user, array $context = []): PromotionParticipation
    {
        $this->assertEnabled();
        if (! $user->isActive()) {
            throw new DomainException('Ein deaktiviertes Konto darf keinen Gewinn einloesen.');
        }
        $digest = $this->tokenDigest($plainToken);

        $result = DB::transaction(function () use ($digest, $user, $context): PromotionParticipation|PromotionWin {
            $user = User::query()->lockForUpdate()->findOrFail($user->getKey());
            if (! $user->isActive()) {
                throw new DomainException('Ein deaktiviertes Konto darf keinen Gewinn einloesen.');
            }
            $candidate = PromotionWin::query()
                ->select(['id', 'campaign_id'])
                ->where('token_hash', $digest)
                ->first();

            if (! $candidate) {
                throw new DomainException('Der Einmal-Code wurde bereits verwendet oder ist ungueltig.');
            }

            $campaign = PromotionCampaign::query()->lockForUpdate()->findOrFail($candidate->campaign_id);
            $win = PromotionWin::query()
                ->whereKey($candidate->getKey())
                ->where('token_hash', $digest)
                ->lockForUpdate()
                ->firstOrFail();

            $this->assertAuditIntegrity($campaign);

            if ($win->status !== PromotionWinStatus::Issued) {
                throw new DomainException('Der Einmal-Code wurde bereits verwendet oder ist ungueltig.');
            }

            if ($win->expires_at->isPast()) {
                $win->forceFill([
                    'status' => PromotionWinStatus::Expired,
                    'expired_at' => now(),
                ])->save();
                $this->audit->append($campaign, 'win.expired', $win, null, $user, [
                    'status' => PromotionWinStatus::Expired->value,
                ], $context);

                return $win;
            }

            if (! $campaign->isOpen()) {
                throw new DomainException('Die Kampagne ist nicht aktiv.');
            }

            $claimKey = hash('sha256', $campaign->getKey().':'.$user->getKey());
            if (PromotionWin::query()->where('claim_key', $claimKey)->exists()) {
                throw new DomainException('Fuer dieses Konto besteht bereits ein Gewinn in dieser Kampagne.');
            }

            $participation = PromotionParticipation::query()
                ->where('campaign_id', $campaign->getKey())
                ->where('user_id', $user->getKey())
                ->lockForUpdate()
                ->first();

            if (! $participation) {
                $participation = PromotionParticipation::query()->create([
                    'campaign_id' => $campaign->getKey(),
                    'user_id' => $user->getKey(),
                    'public_id' => ParticipationId::generate($campaign->code),
                ]);
            }

            $win->forceFill([
                'participation_id' => $participation->getKey(),
                'claim_key' => $claimKey,
                'status' => PromotionWinStatus::Bound,
                'consumed_at' => now(),
                'bound_at' => now(),
            ])->save();

            $this->audit->append($campaign, 'win.bound', $win, $participation, $user, [
                'status' => PromotionWinStatus::Bound->value,
                'user_ref' => hash_hmac('sha256', 'participant:'.$user->getKey(), $this->settings->auditKey()),
            ], $context);

            return $participation->fresh(['campaign', 'currentWin.prize']);
        }, 5);

        if ($result instanceof PromotionWin) {
            throw new DomainException('Der Einmal-Code ist abgelaufen. Die Reservierung muss durch einen Volladmin geprueft werden.');
        }

        return $result;
    }

    public function confirmParticipation(
        PromotionParticipation $participation,
        User $user,
        array $context = [],
    ): PromotionParticipation {
        return $this->transitionByParticipant($participation, $user, PromotionWinStatus::Confirmed, $context);
    }

    public function disputeParticipation(
        PromotionParticipation $participation,
        User $user,
        array $context = [],
    ): PromotionParticipation {
        return $this->transitionByParticipant($participation, $user, PromotionWinStatus::Disputed, $context);
    }

    public function fulfill(PromotionWin $win, User $actor, array $context = []): PromotionWin
    {
        $this->assertEnabled();

        return DB::transaction(function () use ($win, $actor, $context): PromotionWin {
            $actor = User::query()->lockForUpdate()->findOrFail($actor->getKey());
            $candidate = PromotionWin::query()->select(['id', 'campaign_id'])->findOrFail($win->getKey());
            $campaign = PromotionCampaign::query()->lockForUpdate()->findOrFail($candidate->campaign_id);
            $win = PromotionWin::query()->lockForUpdate()->findOrFail($candidate->getKey());

            $this->assertAuditIntegrity($campaign);

            if ($win->status === PromotionWinStatus::Fulfilled) {
                return $win;
            }
            if ($win->status !== PromotionWinStatus::Confirmed) {
                throw new DomainException('Nur ein bestaetigter Gewinn darf ausgegeben werden.');
            }

            $win->loadMissing(['prize', 'participation.user']);
            if (! $win->participation?->user?->hasVerifiedEmail()) {
                throw new DomainException('Die E-Mail-Adresse des Teilnehmers ist noch nicht verifiziert.');
            }

            $isAdmin = $actor->role === 'admin' && (bool) $actor->status;
            $canOnsite = $isAdmin || (method_exists($actor, 'hasRbacPermission')
                && $actor->hasRbacPermission('promotion.fulfillment.onsite'));
            $mode = $win->fulfillment_mode_snapshot;
            if (! $mode instanceof PromotionFulfillmentMode) {
                throw new DomainException('Die bei der QR-Ausgabe gespeicherte Ausgabemethode ist ungueltig.');
            }
            $allowed = $mode === PromotionFulfillmentMode::ExternalAdmin
                ? $isAdmin
                : $canOnsite;

            if (! $allowed) {
                throw new DomainException('Keine Berechtigung fuer diese Ausgabemethode.');
            }

            $win->forceFill([
                'status' => PromotionWinStatus::Fulfilled,
                'fulfilled_by' => $actor->getKey(),
                'fulfilled_at' => now(),
            ])->save();
            $this->audit->append($campaign, 'win.fulfilled', $win, $win->participation, $actor, [
                'fulfillment_mode' => $mode->value,
                'status' => PromotionWinStatus::Fulfilled->value,
            ], $context);

            return $win->fresh(['campaign', 'prize', 'participation']);
        }, 5);
    }

    public function cancel(PromotionWin $win, User $actor, string $reason, array $context = []): PromotionWin
    {
        $this->assertEnabled();
        $reason = trim($reason);
        if ($actor->role !== 'admin' || ! (bool) $actor->status) {
            throw new DomainException('Nur ein Volladmin darf Gewinnvorgaenge stornieren.');
        }
        if (! in_array($reason, self::CANCELLATION_REASONS, true)) {
            throw new DomainException('Fuer die Gegenbuchung ist ein gueltiger strukturierter Pflichtgrund erforderlich.');
        }

        return DB::transaction(function () use ($win, $actor, $reason, $context): PromotionWin {
            $actor = User::query()->lockForUpdate()->findOrFail($actor->getKey());
            if ($actor->role !== 'admin' || ! (bool) $actor->status) {
                throw new DomainException('Nur ein Volladmin darf Gewinnvorgaenge stornieren.');
            }
            $candidate = PromotionWin::query()->select(['id', 'campaign_id'])->findOrFail($win->getKey());
            $campaign = PromotionCampaign::query()->lockForUpdate()->findOrFail($candidate->campaign_id);
            $win = PromotionWin::query()->lockForUpdate()->findOrFail($candidate->getKey());
            $prize = PromotionPrize::query()->lockForUpdate()->findOrFail($win->prize_id);

            $this->assertAuditIntegrity($campaign);

            if ($win->status === PromotionWinStatus::Cancelled) {
                return $win;
            }
            if ($win->status === PromotionWinStatus::Fulfilled) {
                throw new DomainException('Ein bereits ausgegebener Gewinn kann nicht storniert werden.');
            }

            $participation = $win->participation;
            $prize->forceFill(['reserved_count' => max(0, $prize->reserved_count - 1)])->save();
            $win->forceFill([
                'status' => PromotionWinStatus::Cancelled,
                'claim_key' => null,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ])->save();
            $this->audit->append($campaign, 'win.cancelled', $win, $participation, $actor, [
                'reason_digest' => hash('sha256', $reason),
                'status' => PromotionWinStatus::Cancelled->value,
            ], $context);

            return $win->fresh(['campaign', 'prize', 'participation']);
        }, 5);
    }

    public function expire(PromotionWin $win, ?User $actor = null, array $context = []): PromotionWin
    {
        $this->assertEnabled();

        return DB::transaction(function () use ($win, $actor, $context): PromotionWin {
            $candidate = PromotionWin::query()->select(['id', 'campaign_id'])->findOrFail($win->getKey());
            $campaign = PromotionCampaign::query()->lockForUpdate()->findOrFail($candidate->campaign_id);
            $win = PromotionWin::query()->lockForUpdate()->findOrFail($candidate->getKey());

            $this->assertAuditIntegrity($campaign);

            if ($win->status === PromotionWinStatus::Expired) {
                return $win;
            }
            if ($win->status !== PromotionWinStatus::Issued || $win->expires_at->isFuture()) {
                throw new DomainException('Dieser QR-Code kann nicht als abgelaufen markiert werden.');
            }

            $win->forceFill(['status' => PromotionWinStatus::Expired, 'expired_at' => now()])->save();
            $this->audit->append($campaign, 'win.expired', $win, null, $actor, [
                'status' => PromotionWinStatus::Expired->value,
            ], $context);

            return $win;
        }, 5);
    }

    private function transitionByParticipant(
        PromotionParticipation $participation,
        User $user,
        PromotionWinStatus $target,
        array $context,
    ): PromotionParticipation {
        $this->assertEnabled();

        return DB::transaction(function () use ($participation, $user, $target, $context): PromotionParticipation {
            $user = User::query()->lockForUpdate()->findOrFail($user->getKey());
            if (! $user->isActive()) {
                throw new DomainException('Ein deaktiviertes Konto darf einen Gewinn nicht bestaetigen oder beanstanden.');
            }

            $candidate = PromotionParticipation::query()
                ->select(['id', 'campaign_id'])
                ->findOrFail($participation->getKey());
            $campaign = PromotionCampaign::query()->lockForUpdate()->findOrFail($candidate->campaign_id);
            $participation = PromotionParticipation::query()->lockForUpdate()->findOrFail($participation->getKey());
            if ((int) $participation->user_id !== (int) $user->getKey()) {
                throw new DomainException('Diese Teilnahme gehoert zu einem anderen Konto.');
            }

            $win = PromotionWin::query()
                ->where('participation_id', $participation->getKey())
                ->latest('id')
                ->lockForUpdate()
                ->firstOrFail();

            $this->assertAuditIntegrity($campaign);

            if ($win->status === $target) {
                return $participation->fresh(['campaign', 'currentWin.prize']);
            }
            if ($win->status !== PromotionWinStatus::Bound) {
                throw new DomainException('Der Gewinn kann in diesem Status nicht mehr geaendert werden.');
            }

            $timestampColumn = $target === PromotionWinStatus::Confirmed ? 'confirmed_at' : 'disputed_at';
            $win->forceFill(['status' => $target, $timestampColumn => now()])->save();
            $this->audit->append($campaign, 'win.'.$target->value, $win, $participation, $user, [
                'status' => $target->value,
            ], $context);

            return $participation->fresh(['campaign', 'currentWin.prize']);
        }, 5);
    }

    private function assertEnabled(): void
    {
        if (! $this->settings->isEnabled()) {
            throw new DomainException('Die Promotion-Funktion ist nicht vollstaendig konfiguriert oder deaktiviert.');
        }
    }

    private function assertAuditIntegrity(PromotionCampaign $campaign): void
    {
        if (! $this->audit->verify($campaign)) {
            throw new DomainException('Die Promotion-Konfiguration, der Gewinnzustand oder die Auditkette ist ungueltig; es wird keine Zustandsaenderung ausgefuehrt.');
        }
    }

    private function assertCanRecord(User $actor): void
    {
        $allowed = ($actor->role === 'admin' && (bool) $actor->status)
            || (method_exists($actor, 'hasRbacPermission') && $actor->hasRbacPermission('promotion.wins.record'));

        if (! $allowed) {
            throw new DomainException('Keine Berechtigung zur Gewinnerfassung.');
        }
    }

    private function newPlainToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    private function tokenDigest(string $plainToken): string
    {
        if (! preg_match('/\A[A-Za-z0-9_-]{43}\z/', $plainToken)) {
            throw new DomainException('Ungueltiges Tokenformat.');
        }

        return hash('sha256', $plainToken);
    }
}

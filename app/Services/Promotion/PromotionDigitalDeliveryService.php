<?php

namespace App\Services\Promotion;

use App\Enums\PromotionDigitalDeliveryStatus;
use App\Enums\PromotionFulfillmentMode;
use App\Enums\PromotionGiftCodeStatus;
use App\Enums\PromotionNotificationStatus;
use App\Mail\PromotionDigitalDeliveryMail;
use App\Models\PromotionGiftCode;
use App\Models\PromotionNotificationDelivery;
use App\Models\PromotionSpinResult;
use App\Models\User;
use DomainException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

/** Direct, request-bound code delivery. No queue, command, job or scheduler is used. */
final class PromotionDigitalDeliveryService
{
    public function __construct(
        private readonly PromotionAuditChain $audit,
        private readonly PromotionSettingsService $settings,
    ) {}

    /** @return array{created:int,duplicates:int} */
    public function addCodes(int $prizeId, string $source, User $admin): array
    {
        $this->assertAdmin($admin);
        $lines = collect(preg_split('/\R/u', $source) ?: [])->map(fn (string $line) => trim($line))->filter()->values();
        if ($lines->isEmpty()) throw new DomainException('Bitte mindestens einen Gutscheincode einfügen.');
        if ($lines->count() !== $lines->unique()->count()) throw new DomainException('Der eingefügte Vorrat enthält doppelte Gutscheincodes.');

        $result = DB::transaction(function () use ($prizeId, $lines, $admin): array {
            $admin = User::query()->lockForUpdate()->findOrFail($admin->getKey()); $this->assertAdmin($admin);
            $prize = \App\Models\PromotionPrize::query()->lockForUpdate()->findOrFail($prizeId);
            if ($prize->fulfillment_mode !== PromotionFulfillmentMode::ExternalAdmin) throw new DomainException('Codes sind nur für externe digitale Gewinne zulässig.');
            $campaign = $prize->campaign()->lockForUpdate()->firstOrFail();
            $this->assertAudit($campaign);
            $created = 0; $duplicates = 0;
            foreach ($lines as $code) {
                $fingerprint = $this->fingerprint($code);
                if (PromotionGiftCode::query()->where('code_fingerprint', $fingerprint)->exists()) { $duplicates++; continue; }
                PromotionGiftCode::query()->create([
                    'campaign_id' => $campaign->getKey(), 'prize_id' => $prize->getKey(),
                    'code_encrypted' => Crypt::encryptString($code), 'code_fingerprint' => $fingerprint,
                    'status' => PromotionGiftCodeStatus::Available, 'uploaded_by' => $admin->getKey(),
                ]); $created++;
            }
            if ($created) $this->audit->appendV2($campaign, 'digital.codes_added', null, $admin, ['prize_id' => $prize->getKey(), 'count' => $created]);
            return compact('created', 'duplicates');
        }, 5);

        // Delivery starts only after the inventory transaction committed.
        if ($result['created']) $this->deliverWaitingForPrize($prizeId);
        return $result;
    }

    public function approve(PromotionSpinResult $result, User $admin): PromotionSpinResult
    {
        $this->assertAdmin($admin);
        $approved = DB::transaction(function () use ($result, $admin): PromotionSpinResult {
            $admin = User::query()->lockForUpdate()->findOrFail($admin->getKey()); $this->assertAdmin($admin);
            $result = PromotionSpinResult::query()->with('ticket')->lockForUpdate()->findOrFail($result->getKey());
            $campaign = $result->campaign()->lockForUpdate()->firstOrFail(); $this->assertAudit($campaign);
            if ($result->is_test || ! $result->is_final || $result->superseded_at || $result->fulfilled_at || $result->fulfillment_mode_snapshot !== PromotionFulfillmentMode::ExternalAdmin) throw new DomainException('Dieser Gewinn kann nicht digital freigegeben werden.');
            if (! $result->digital_delivery_approved_at) {
                $result->forceFill(['digital_delivery_approved_by' => $admin->getKey(), 'digital_delivery_approved_at' => now(), 'digital_delivery_status' => PromotionDigitalDeliveryStatus::AwaitingCode])->save();
                $this->audit->appendV2($campaign, 'digital.approved', $result->ticket?->participation, $admin, [], $result->ticket, $result->turn, $result);
            }
            return $result->fresh(['ticket.participation.user', 'campaign', 'prize']);
        }, 5);
        $this->sendNotice($approved, 'approved');
        return $this->attemptDelivery($approved);
    }

    public function attemptDelivery(PromotionSpinResult $result): PromotionSpinResult
    {
        $reserved = DB::transaction(function () use ($result): PromotionSpinResult {
            $result = PromotionSpinResult::query()->with(['ticket.user.customer', 'ticket.participation', 'turn', 'prize'])->lockForUpdate()->findOrFail($result->getKey());
            if (! $result->digital_delivery_approved_at || $result->fulfilled_at || $result->is_test) return $result;
            $campaign = $result->campaign()->lockForUpdate()->firstOrFail(); $this->assertAudit($campaign);
            $user = $result->ticket?->user;
            if (! $user || ! $this->hasDeliveryProfile($user)) {
                $result->forceFill(['digital_delivery_status' => PromotionDigitalDeliveryStatus::AwaitingProfile])->save();
                $this->audit->appendV2($campaign, 'digital.profile_blocked', $result->ticket?->participation, null, [], $result->ticket, $result->turn, $result);
                return $result->fresh(['ticket.user', 'campaign']);
            }
            $code = PromotionGiftCode::query()->where('spin_result_id', $result->getKey())->lockForUpdate()->first();
            if (! $code) $code = PromotionGiftCode::query()->where('prize_id', $result->prize_id)->where('status', PromotionGiftCodeStatus::Available)->orderBy('id')->lockForUpdate()->first();
            if (! $code) {
                $result->forceFill(['digital_delivery_status' => PromotionDigitalDeliveryStatus::AwaitingCode])->save();
                $this->audit->appendV2($campaign, 'digital.code_unavailable', $result->ticket?->participation, null, [], $result->ticket, $result->turn, $result);
                return $result->fresh(['ticket.user', 'campaign']);
            }
            if ($code->spin_result_id === null) $code->forceFill(['spin_result_id' => $result->getKey(), 'status' => PromotionGiftCodeStatus::Reserved, 'reserved_at' => now()])->save();
            $result->forceFill(['digital_delivery_status' => PromotionDigitalDeliveryStatus::Reserved])->save();
            $this->audit->appendV2($campaign, 'digital.code_reserved', $result->ticket?->participation, null, ['code_id' => $code->getKey()], $result->ticket, $result->turn, $result);
            return $result->fresh(['ticket.user', 'campaign', 'giftCode']);
        }, 5);

        if ($reserved->digital_delivery_status === PromotionDigitalDeliveryStatus::AwaitingProfile) $this->sendNotice($reserved, 'profile_required');
        if ($reserved->digital_delivery_status !== PromotionDigitalDeliveryStatus::Reserved) return $reserved;
        return $this->sendReservedCode($reserved);
    }

    public function retry(PromotionSpinResult $result, User $admin): PromotionSpinResult
    {
        $this->assertAdmin($admin);
        return $this->attemptDelivery($result);
    }

    public function deliverEligibleForUser(User $user): void
    {
        PromotionSpinResult::query()->whereHas('ticket', fn ($q) => $q->where('user_id', $user->getKey()))
            ->whereNotNull('digital_delivery_approved_at')->whereNull('fulfilled_at')->where('is_test', false)->each(fn (PromotionSpinResult $result) => $this->attemptDelivery($result));
    }

    public function profileComplete(User $user): bool
    {
        $user->loadMissing('customer');
        return $this->hasDeliveryProfile($user);
    }

    private function deliverWaitingForPrize(int $prizeId): void
    {
        PromotionSpinResult::query()->where('prize_id', $prizeId)->whereNotNull('digital_delivery_approved_at')->whereNull('fulfilled_at')->where('is_test', false)->each(fn (PromotionSpinResult $result) => $this->attemptDelivery($result));
    }

    private function sendReservedCode(PromotionSpinResult $result): PromotionSpinResult
    {
        $result->loadMissing(['ticket.user', 'campaign', 'giftCode']); $code = $result->giftCode;
        if (! $code) return $result;
        try {
            Mail::to($result->ticket->user->email)->send(new PromotionDigitalDeliveryMail($result, 'code', rtrim($this->settings->redemptionBaseUrl(), '/').'/gluecksrad', Crypt::decryptString($code->getRawOriginal('code_encrypted'))));
        } catch (Throwable $exception) {
            return $this->markCodeMail($result, false, $exception);
        }
        return $this->markCodeMail($result, true);
    }

    private function sendNotice(PromotionSpinResult $result, string $type): void
    {
        $delivery = DB::transaction(function () use ($result, $type): PromotionNotificationDelivery {
            $delivery = PromotionNotificationDelivery::query()->firstOrCreate(['spin_result_id' => $result->getKey(), 'type' => $type], ['status' => PromotionNotificationStatus::Open]);
            $delivery->forceFill(['last_attempted_at' => now()])->save(); return $delivery;
        });
        try {
            $result->loadMissing('ticket.user');
            Mail::to($result->ticket->user->email)->send(new PromotionDigitalDeliveryMail($result, $type, rtrim($this->settings->redemptionBaseUrl(), '/').'/gluecksrad'));
            $delivery->forceFill(['status' => PromotionNotificationStatus::Sent, 'sent_at' => now(), 'failed_at' => null, 'error_digest' => null])->save();
        } catch (Throwable $exception) {
            $delivery->forceFill(['status' => PromotionNotificationStatus::Failed, 'failed_at' => now(), 'error_digest' => hash('sha256', $exception::class.'|'.$exception->getMessage())])->save(); report($exception);
        }
    }

    private function markCodeMail(PromotionSpinResult $result, bool $sent, ?Throwable $error = null): PromotionSpinResult
    {
        return DB::transaction(function () use ($result, $sent, $error): PromotionSpinResult {
            $result = PromotionSpinResult::query()->with(['giftCode', 'ticket.participation', 'turn'])->lockForUpdate()->findOrFail($result->getKey());
            $code = PromotionGiftCode::query()->lockForUpdate()->findOrFail($result->giftCode->getKey());
            $campaign = $result->campaign()->lockForUpdate()->firstOrFail();
            $notice = PromotionNotificationDelivery::query()->firstOrCreate(['spin_result_id' => $result->getKey(), 'type' => 'code'], ['status' => PromotionNotificationStatus::Open]);
            $now = now();
            if ($sent) {
                $code->forceFill(['status' => PromotionGiftCodeStatus::Sent, 'sent_at' => $now])->save();
                $result->forceFill(['digital_delivery_status' => PromotionDigitalDeliveryStatus::Sent, 'fulfilled_at' => $now, 'fulfilled_by' => $result->digital_delivery_approved_by])->save();
                $notice->forceFill(['status' => PromotionNotificationStatus::Sent, 'sent_at' => $now, 'last_attempted_at' => $now, 'failed_at' => null, 'error_digest' => null])->save();
                $result->ticket?->user?->receiveMessage('Dein Gewinn wurde versendet', 'Dein digitaler Gewinn wurde per E-Mail versendet. Der Gutscheincode ist aus Sicherheitsgründen nur in dieser E-Mail enthalten.', $result->digital_delivery_approved_by);
                $this->audit->appendV2($campaign, 'digital.code_sent', $result->ticket?->participation, null, ['code_id' => $code->getKey()], $result->ticket, $result->turn, $result);
            } else {
                $code->forceFill(['status' => PromotionGiftCodeStatus::Failed])->save();
                $result->forceFill(['digital_delivery_status' => PromotionDigitalDeliveryStatus::Failed])->save();
                $notice->forceFill(['status' => PromotionNotificationStatus::Failed, 'failed_at' => $now, 'last_attempted_at' => $now, 'error_digest' => hash('sha256', $error::class.'|'.$error->getMessage())])->save();
                $this->audit->appendV2($campaign, 'digital.code_failed', $result->ticket?->participation, null, ['code_id' => $code->getKey()], $result->ticket, $result->turn, $result);
            }
            return $result->fresh(['ticket.user', 'campaign', 'giftCode', 'notificationDeliveries']);
        }, 5);
    }

    private function hasDeliveryProfile(User $user): bool
    {
        $customer = $user->customer;
        return $customer && collect([$customer->first_name, $customer->last_name, $customer->street, $customer->postal_code, $customer->city, $customer->country])->every(fn ($value) => trim((string) $value) !== '');
    }

    private function fingerprint(string $code): string { return hash_hmac('sha256', trim($code), (string) config('app.key')); }
    private function assertAdmin(User $user): void { if ($user->role !== 'admin' || ! (bool) $user->status) throw new DomainException('Nur ein aktiver Volladmin darf digitale Gewinne verwalten.'); }
    private function assertAudit(\App\Models\PromotionCampaign $campaign): void { if (! $this->audit->verify($campaign)) throw new DomainException('Die Auditkette der Kampagne ist ungültig.'); }
}

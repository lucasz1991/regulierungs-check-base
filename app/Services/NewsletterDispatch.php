<?php

namespace App\Services;

use App\Jobs\DeliverNewsletter;
use App\Models\Post;
use App\Models\Setting;
use App\Notifications\MailNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Throwable;

class NewsletterDispatch
{
    public function queueDue(): void
    {
        DB::table('newsletter_campaigns')->where(function ($query) {
            $query->where('status', 'sending')->orWhere(function ($scheduled) {
                $scheduled->where('status', 'scheduled')->where('scheduled_at', '<=', now('UTC'));
            });
        })->select('id')->chunkById(50, function ($campaigns) {
            foreach ($campaigns as $campaign) {
                $this->queueCampaign($campaign->id);
            }
        });
    }

    private function queueCampaign(int $id): void
    {
        DB::transaction(function () use ($id) {
            $campaign = DB::table('newsletter_campaigns')->where('id', $id)->lockForUpdate()->first();
            if (! $campaign || ! in_array($campaign->status, ['scheduled', 'sending'], true)
                || ($campaign->status === 'scheduled' && (! $campaign->scheduled_at || $campaign->scheduled_at > now('UTC')->toDateTimeString()))) {
                return;
            }
            $deliveries = DB::table('newsletter_deliveries')->where('campaign_id', $id);
            // A worker lost after claiming a message may already have handed it to SMTP.
            (clone $deliveries)->where('status', 'processing')->where('updated_at', '<', now('UTC')->subMinutes(15))
                ->update(['status' => 'uncertain', 'updated_at' => now('UTC')]);
            if (! (clone $deliveries)->whereIn('status', ['pending', 'processing'])->exists()) {
                $this->setCampaignStatus($id, 'sent');

                return;
            }
            if (! $this->articleIsAvailable($campaign)) {
                $this->setCampaignStatus($id, 'blocked');

                return;
            }
            $this->setCampaignStatus($id, 'sending');
            $ids = (clone $deliveries)->where('status', 'pending')->where(function ($q) {
                $q->whereNull('queued_at')->orWhere('queued_at', '<', now('UTC')->subMinutes(15));
            })->orderBy('id')->limit(500)->pluck('id');
            // The existing database queue and its reservations are committed together.
            foreach ($ids->chunk(50) as $batch) {
                (clone $deliveries)->whereIn('id', $batch)->update(['queued_at' => now('UTC')]);
                foreach ($batch as $recipientId) {
                    DeliverNewsletter::dispatch($id, $recipientId)->onConnection('database')->onQueue('kundenmail')->beforeCommit();
                }
            }
        }, 3);
    }

    private function articleIsAvailable(object $campaign): bool
    {
        return $campaign->post_id === null || (Setting::enabled('webcontent', 'news_enabled')
            && Post::whereKey($campaign->post_id)->where('type', 'news')->published()->exists());
    }

    private function setCampaignStatus(int $id, string $status): void
    {
        DB::table('newsletter_campaigns')->where('id', $id)->where('status', '!=', $status)
            ->update(['status' => $status, 'updated_at' => now('UTC')]);
    }

    public function deliver(int $campaignId, int $deliveryId): void
    {
        $payload = DB::transaction(function () use ($campaignId, $deliveryId) {
            $campaign = DB::table('newsletter_campaigns')->where('id', $campaignId)->lockForUpdate()->first();
            if (! $campaign || $campaign->status !== 'sending') {
                return null;
            }
            $row = DB::table('newsletter_deliveries')->where('campaign_id', $campaignId)->where('id', $deliveryId)->lockForUpdate()->first();
            if (! $row || $row->status !== 'pending') {
                return null;
            }
            if (! $this->articleIsAvailable($campaign)) {
                $this->setCampaignStatus($campaignId, 'blocked');

                return null;
            }
            $recipient = app(NewsletterSubscriptions::class)->recipient($row->subscription_id, $row->email);
            if ($recipient === null) {
                $this->finishDelivery($deliveryId, 'pending', 'skipped');

                return null;
            }
            $links = app(NewsletterPreferenceLinks::class)->forRecipient($row->subscription_id, $row->email);
            $content = [
                'subject' => $campaign->subject,
                'header' => filled($campaign->greeting) ? $campaign->greeting : (filled($recipient['name']) ? 'Hallo '.$recipient['name'].',' : 'Hallo,'),
                'body' => $campaign->body,
                'link' => $campaign->post_id ? route('newsletter.article', $campaign->post_id) : $campaign->link,
                'button_label' => $campaign->button_label,
                'newsletter' => $links + [
                    'kind' => $campaign->post_id ? 'news' : 'free',
                    'preheader' => $campaign->preheader ?? null,
                    'title' => $campaign->post_id ? $campaign->article_title : null,
                    'image' => $campaign->include_image && $campaign->image_url ? [
                        'url' => $campaign->image_url, 'alt' => $campaign->image_alt,
                        'width' => (int) $campaign->image_width, 'height' => (int) $campaign->image_height,
                    ] : null,
                ],
            ];
            $this->finishDelivery($deliveryId, 'pending', 'processing');

            return [$row->email, $content];
        }, 3);
        if ($payload === null) {
            return;
        }
        [$email, $content] = $payload;
        try {
            Notification::sendNow(Notification::route('mail', $email), new MailNotification($content));
        } catch (Throwable $exception) {
            report($exception);
            // A transport exception can occur after the receiving server accepted the message.
            $this->finishDelivery($deliveryId, 'processing', 'uncertain');

            return;
        }
        // A late, positive transport acknowledgement also resolves an uncertain claim.
        DB::table('newsletter_deliveries')->where('id', $deliveryId)->whereIn('status', ['processing', 'uncertain'])
            ->update(['status' => 'sent', 'updated_at' => now('UTC')]);
    }

    private function finishDelivery(int $id, string $from, string $to): void
    {
        DB::table('newsletter_deliveries')->where('id', $id)->where('status', $from)
            ->update(['status' => $to, 'updated_at' => now('UTC')]);
    }

    public function jobFailed(int $campaignId, int $id): void
    {
        // Only reservations still pending at exhaustion can be retried with certainty.
        DB::table('newsletter_deliveries')->where('campaign_id', $campaignId)->where('id', $id)
            ->where('status', 'pending')->update(['status' => 'failed', 'updated_at' => now('UTC')]);
    }
}

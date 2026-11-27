<?php

namespace App\Services;

use App\Notifications\PublicNewsletterConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

class PublicNewsletterSignup
{
    public function request(string $input): void
    {
        $email = NewsletterSubscriptions::email($input);
        abort_unless($email, 422);

        DB::transaction(function () use ($email) {
            if ($this->claimedOrSuppressed($email)) {
                return;
            }
            app(NewsletterSubscriptions::class)->registerInterest($email);
            $row = DB::table('newsletter_subscriptions')->where('source_key', 'public:'.hash('sha256', $email))->lockForUpdate()->first();
            if (! $row || ! $row->enabled || $row->consented_at || $row->email !== $email) {
                return;
            }
            $events = DB::table('newsletter_preference_events')->where('subscription_id', $row->id);
            if ($events->where('event', 'confirmation_requested')->where('created_at', '>', now()->subRealHours(24))->exists()) {
                return;
            }
            $expires = now('UTC')->addHours(24);
            $relative = URL::temporarySignedRoute('newsletter.confirm', $expires, [
                'subscription' => $row->id, 'address' => $row->address_token, 'v' => $row->version,
            ], false);
            $this->event($row, 'confirmation_requested');
            // Both the invitation and its cooldown are committed to the same database.
            Notification::route('mail', $email)->notify((new PublicNewsletterConfirmation(
                $row->id, $row->address_token, $row->version, $expires->timestamp, rtrim(config('app.url'), '/').$relative
            ))->onConnection('database')->onQueue('kundenmail')->beforeCommit());
        }, 3);
    }

    public function current(int $id, string $address, bool $lock = false): ?object
    {
        $row = DB::table('newsletter_subscriptions')->where('id', $id)->where('source_type', 'public')
            ->where('address_token', $address)->where('enabled', true)
            ->when($lock, fn ($query) => $query->lockForUpdate())->first();

        return $row && $row->email && $row->source_key === 'public:'.hash('sha256', $row->email)
            && ! $this->claimedOrSuppressed($row->email) ? $row : null;
    }

    public function state(int $id, string $address, int $version, bool $confirm): object
    {
        return DB::transaction(function () use ($id, $address, $version, $confirm) {
            $row = $this->current($id, $address, $confirm);
            abort_unless($row, 404);
            if ($row->consented_at) {
                abort_unless($row->consent_source === 'public_confirmation' && (int) $row->version === $version + 1, 410);

                return $row;
            }
            abort_unless((int) $row->version === $version, 410);
            if ($confirm) {
                $row->consented_at = now();
                DB::table('newsletter_subscriptions')->where('id', $id)->update([
                    'consented_at' => $row->consented_at, 'consent_source' => 'public_confirmation',
                    'consent_note' => NewsletterSubscriptions::AGREEMENT, 'version' => $version + 1, 'updated_at' => now(),
                ]);
                $this->event($row, 'consent', NewsletterSubscriptions::AGREEMENT);
            }

            return $row;
        }, 3);
    }

    private function claimedOrSuppressed(string $email): bool
    {
        return DB::table('newsletter_suppressions')->where('email', $email)->exists()
            || DB::table('users')->whereRaw('LOWER(TRIM(email)) = ?', [$email])->exists()
            || DB::table('contacts')->whereRaw('LOWER(TRIM(email)) = ?', [$email])->exists();
    }

    private function event(object $row, string $event, ?string $note = null): void
    {
        DB::table('newsletter_preference_events')->insert([
            'subscription_id' => $row->id, 'email' => $row->email, 'event' => $event,
            'source' => 'public_confirmation', 'note' => $note, 'created_at' => now(),
        ]);
    }
}

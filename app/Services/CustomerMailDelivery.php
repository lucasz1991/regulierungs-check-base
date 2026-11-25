<?php

namespace App\Services;

use App\Models\Mail;
use App\Models\User;
use App\Notifications\MailNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Throwable;

class CustomerMailDelivery
{
    public function deliver(Mail $mail, int $index, bool $redelivered = false): void
    {
        // Claim one recipient. A repeated or concurrent job cannot send it twice.
        $to = DB::transaction(function () use ($mail, $index, $redelivered) {
            $record = Mail::query()->lockForUpdate()->findOrFail($mail->id);
            $recipient = $record->recipients[$index] ?? [];
            if ($redelivered && ($recipient['state'] ?? '') === 'processing') {
                $this->saveRecipient($record, $index, $recipient, 'uncertain');

                return null;
            }
            if (($recipient['state'] ?? '') !== 'pending') {
                return null;
            }
            $user = isset($recipient['user_id']) ? User::find($recipient['user_id']) : null;
            $email = trim($recipient['email'] ?? '');
            $sendEmail = in_array($record->type, ['mail', 'both'], true);
            $inbox = in_array($record->type, ['message', 'both'], true);
            $contactId = $recipient['contact_id'] ?? null;
            $allowed = ! $contactId || DB::table('contacts')->where('id', $contactId)
                ->where('email', $email)->where('mail_permission', 'allowed')->exists();

            if ((! $inbox && ! $sendEmail) || ($inbox && ! $user) || ! $allowed ||
                ($sendEmail && (! filter_var($email, FILTER_VALIDATE_EMAIL) ||
                    (isset($recipient['user_id']) && (! $user || strcasecmp($user->email ?? '', $email) !== 0))))) {
                $this->saveRecipient($record, $index, $recipient, 'skipped');

                return null;
            }

            if ($inbox && empty($recipient['message_sent'])) {
                $user->receiveMessage($record->content['subject'], nl2br(e($record->content['body'])),
                    $record->content['from_user_id'] ?? 1, $record->files);
                $recipient['message_sent'] = true;
            }
            $this->saveRecipient($record, $index, $recipient, $sendEmail ? 'processing' : 'sent');

            return $sendEmail ? Notification::route('mail', $email) : null;
        });
        if (! $to) {
            return;
        }

        try {
            // Execute transport in the Base worker, without queuing a second notification on default.
            Notification::sendNow($to, new MailNotification($mail));
        } catch (Throwable $e) {
            report($e);
            $this->finish($mail, $index, 'failed');

            return;
        }
        $this->finish($mail, $index, 'sent');
    }

    public function jobFailed(Mail $mail, int $index): void
    {
        DB::transaction(function () use ($mail, $index) {
            $record = Mail::query()->lockForUpdate()->find($mail->id);
            $recipient = $record?->recipients[$index] ?? [];
            $state = $recipient['state'] ?? '';
            if (in_array($state, ['processing', 'pending'], true)) {
                $this->saveRecipient($record, $index, $recipient, $state === 'processing' ? 'uncertain' : 'failed');
            }
        });
    }

    private function finish(Mail $mail, int $index, string $state): void
    {
        DB::transaction(function () use ($mail, $index, $state) {
            $record = Mail::query()->lockForUpdate()->findOrFail($mail->id);
            $recipient = $record->recipients[$index];
            if (in_array($recipient['state'], ['processing', 'uncertain'], true)) {
                $this->saveRecipient($record, $index, $recipient, $state);
            }
        });
    }

    private function saveRecipient(Mail $record, int $index, array $recipient, string $state): void
    {
        $recipients = $record->recipients;
        $recipient['state'] = $state;
        $recipient['status'] = $state === 'sent';
        $recipients[$index] = $recipient;
        $record->update(['recipients' => $recipients, 'status' => collect($recipients)->every('status', true)]);
    }
}

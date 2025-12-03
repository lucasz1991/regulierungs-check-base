<?php

namespace App\Notifications;

use App\Models\ClaimRating;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class ClaimRatingMissingEmailVerificationReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var \Illuminate\Support\Collection|\App\Models\ClaimRating[] */
    public Collection $claimRatings;

    /**
     * @param \Illuminate\Support\Collection<int,\App\Models\ClaimRating> $claimRatings
     */
    public function __construct(Collection $claimRatings)
    {
        $this->claimRatings = $claimRatings;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $count = $this->claimRatings->count();

        // Laravel Standard-Verifizierungslink
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(180),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->email)]
        );

        return (new MailMessage)
            ->subject('Bitte bestätige deine E-Mail-Adresse für deine Bewertung')
            ->greeting('Hallo ' . ($notifiable->name ?: ''))
            ->line('Du hast auf Regulierungs-CHECK bereits eine oder mehrere Schadenbewertungen abgegeben.')
            ->line("Aktuell können **{$count} Bewertung(en)** nicht veröffentlicht werden, da deine E-Mail-Adresse noch nicht bestätigt wurde.")
            ->line('Die E-Mail-Bestätigung ist notwendig, um sicherzustellen, dass Bewertungen nur von echten Nutzern stammen und um Missbrauch zu verhindern.')
            ->action('E-Mail bestätigen', $verificationUrl)
            ->line('Falls du keine Bestätigungs-Mail erhalten hast, kannst du dir in deinem Profilbereich eine neue Bestätigung zusenden lassen.')
            ->salutation('Mit freundlichen Grüßen<br>dein Regulierungs-CHECK Team');
    }
}

<?php

namespace App\Notifications;

use App\Services\PublicNewsletterSignup;

class PublicNewsletterConfirmation extends MailNotification
{
    public int $tries = 1;

    public function __construct(private int $subscription, private string $addressToken, private int $version, private int $expires, string $link)
    {
        parent::__construct([
            'subject' => 'Deine Anmeldung zum Regulierungs-CHECK Newsletter',
            'header' => 'Hallo,',
            'body' => 'Bitte bestätige deine E-Mail-Adresse, wenn du unseren Newsletter erhalten möchtest. Der Link gilt 24 Stunden.'
                ."\n\n".'Falls du dich nicht angemeldet hast, kannst du diese Nachricht ignorieren.',
            'link' => $link, 'button_label' => 'Anmeldung prüfen',
        ]);
    }

    public function shouldSend(object $notifiable, string $channel): bool
    {
        if (now()->timestamp >= $this->expires) {
            return false;
        }
        $row = app(PublicNewsletterSignup::class)->current($this->subscription, $this->addressToken);

        return $row && ! $row->consented_at && (int) $row->version === $this->version
            && $row->email === $notifiable->routeNotificationFor($channel);
    }
}

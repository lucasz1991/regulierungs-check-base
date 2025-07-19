<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PublicFormNotification extends Notification
{
    use Queueable;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $formType = $this->data['form_type'] ?? 'unbekannt';

        $mail = (new MailMessage);

        switch ($formType) {
            case 'newsletter':
                $mail->subject('Neue Newsletter-Anmeldung')
                    ->greeting('Neue Newsletter-Anmeldung')
                    ->line('Ein Nutzer hat sich für den Newsletter angemeldet:');
                break;

            case 'kontakt':
                $mail->subject('Neue Kontaktanfrage')
                    ->greeting('Neue Kontaktanfrage')
                    ->line('Ein Nutzer hat das Kontaktformular ausgefüllt:');
                break;

            default:
                $mail->subject('Neue Formularübermittlung')
                    ->greeting('Neue Formularübermittlung')
                    ->line('Ein Formular wurde ausgefüllt:');
                break;
        }

        $mail->line('');

        foreach ($this->data as $key => $value) {
            if ($key === 'form_type') continue; // form_type nicht anzeigen
            $mail->line(ucfirst($key) . ': ' . $value);
        }
        $mail->salutation('Mit freundlichen Grüßen, dein Regulierungs-Check Team');
        return $mail;
    }

}


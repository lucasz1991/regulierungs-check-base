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
        $mail = (new MailMessage)
            ->subject('Neue Formularübermittlung')
            ->greeting('Hallo!')
            ->line('Ein Nutzer hat ein Formular ausgefüllt:')
            ->line('');

        foreach ($this->data as $key => $value) {
            $mail->line(ucfirst($key) . ': ' . $value);
        }

        return $mail->line('--- Ende der Nachricht ---');
    }
}


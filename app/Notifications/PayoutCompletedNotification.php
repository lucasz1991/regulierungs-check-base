<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Payout;

class PayoutCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payout;
    protected $method;

    /**
     * Konstruktor.
     *
     * @param Payout $payout
     */
    public function __construct(Payout $payout)
    {
        $this->payout = $payout;
        $this->method = isset($payout->payout_details['paypal_email']) ? 'PayPal' : (isset($payout->payout_details['iban']) ? 'Banküberweisung' : 'Unbekannt');
    }

    /**
     * Versandkanäle der Notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * E-Mail-Benachrichtigung.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Deine Auszahlung wurde erfolgreich bearbeitet!')
            ->greeting('Hallo ' . $notifiable->name . ',')
            ->line('Gute Neuigkeiten! Deine Auszahlung wurde erfolgreich bearbeitet und an dich überwiesen.')
            ->line('Betrag: ' . number_format($this->payout->amount, 2, ',', '.') . ' €')
            ->line('Auszahlungsmethode: ' . $this->method);

        if ($this->method === 'Banküberweisung') {
            $mail->line('IBAN: ' . $this->payout->payout_details['iban'])
                 ->line('BIC: ' . $this->payout->payout_details['bic']);
        } elseif ($this->method === 'PayPal') {
            $mail->line('PayPal-Konto: ' . $this->payout->payout_details['paypal_email']);
        }

        return $mail
            ->line('Datum der Auszahlung: ' . $this->payout->updated_at)
            ->line('Vielen Dank, dass du MiniFinds nutzt!')
            ->salutation('Liebe Grüße, dein MiniFinds-Team');
    }
}

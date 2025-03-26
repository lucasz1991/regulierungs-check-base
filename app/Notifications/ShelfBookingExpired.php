<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class ShelfBookingExpired extends Notification implements ShouldQueue
{
    use Queueable;

    protected $shelfBooking;

    /**
     * Konstruktor.
     *
     * @param $shelfBooking
     */
    public function __construct($shelfBooking)
    {
        $this->shelfBooking = $shelfBooking;
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
        $userName = $this->shelfBooking->user->name ?? 'Kunde';

        return (new MailMessage)
            ->subject('Deine Regalbuchung ist abgelaufen')
            ->greeting('Hallo ' . $userName . ',')
            ->line('Deine Regalbuchung ist abgelaufen.')
            ->line('Enddatum: ' . Carbon::parse($this->shelfBooking->rental_end)->format('d.m.Y'))
            ->line('Bitte überprüfe dein Regal und fordere deinen Erlös an.')
            ->line('Die nicht verkauften Produkte kannst du nun wieder abholen.')
            ->salutation('Liebe Grüße, dein MiniFinds-Team');
    }
}


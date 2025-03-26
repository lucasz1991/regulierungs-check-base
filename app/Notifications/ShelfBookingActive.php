<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class ShelfBookingActive extends Notification implements ShouldQueue
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
        return ['mail']; // Optional: ['mail', 'database']
    }

    /**
     * E-Mail-Benachrichtigung.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Heute geht es los mit deiner Regalbuchung!')
            ->greeting('Hallo ' . $notifiable->name . ',')
            ->line('Heute startet deine Regalbuchung bei MiniFinds!')
            ->line('Du kannst ab sofort dein Regal einräumen.')
            ->line('Hier sind die Details deiner Buchung:')
            ->line('Startdatum: ' . Carbon::parse($this->shelfBooking->rental_start)->format('d.m.Y'))
            ->line('Enddatum: ' . Carbon::parse($this->shelfBooking->rental_end)->format('d.m.Y'))
            ->action('Regalbuchung ansehen', url('/shelf-rental/' . $this->shelfBooking->id))
            ->line('Wir freuen uns, dass du dabei bist und wünschen dir viel Erfolg mit deinen Verkäufen!')
            ->salutation('Liebe Grüße, dein MiniFinds-Team');
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class RentalReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $rental;
    protected $message;

    /**
     * Konstruktor.
     *
     * @param $rental
     * @param $message
     */
    public function __construct($rental, $message)
    {
        $this->rental = $rental;
        $this->message = $message;
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
        return (new MailMessage)
            ->subject('Erinnerung: Regalbuchung')
            ->greeting('Hallo ' . $notifiable->name . ',')
            ->line($this->message)
            ->line('Startdatum: ' . Carbon::parse($this->rental->rental_start)->format('d.m.Y'))
            ->line('Enddatum: ' . Carbon::parse($this->rental->rental_end)->format('d.m.Y'))
            ->action('Details ansehen', url('/shelf-rental/' . $this->rental->id))
            ->line('Vielen Dank, dass du MiniFinds nutzt!')
            ->salutation('Liebe Grüße, dein MiniFinds-Team');
    }
}

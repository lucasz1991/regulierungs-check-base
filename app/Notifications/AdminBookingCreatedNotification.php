<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminBookingCreatedNotification extends Notification
{
    use Queueable;

    public $admin;
    public $shelfRental;
    public $bookingDetailsUrl;

    /**
     * Create a new notification instance.
     *
     * @param object $admin
     * @param object $shelfRental
     * @param string $bookingDetailsUrl
     */
    public function __construct( $shelfRental, $bookingDetailsUrl)
    {
        $this->shelfRental = $shelfRental;
        $this->bookingDetailsUrl = $bookingDetailsUrl;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('MiniFinds-App: Neue Regalbuchung')
            ->greeting('Hallo !')
            ->line('Es wurde eine neue Regalbuchung vorgenommen. Hier sind die Details:')
            ->line('**Kunde:** ' . $this->shelfRental->customer->user->name)
            ->line('**Regalnummer:** ' . $this->shelfRental->shelf->floor_number)
            ->line('**Buchungszeitraum:** ' . $this->shelfRental->rental_start . ' bis ' . $this->shelfRental->rental_end)
            ->line('**Gesamtpreis:** ' . number_format($this->shelfRental->total_price, 2, ',', '.') . ' €')
            ->action('Details anzeigen', $this->bookingDetailsUrl)
            ->line('Bitte überprüfe die Buchung und nimm gegebenenfalls Kontakt mit dem Kunden auf.')
            ->salutation('Liebe Grüße, deine MiniFinds-App');
    }
}

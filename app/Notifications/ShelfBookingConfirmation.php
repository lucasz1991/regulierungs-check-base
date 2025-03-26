<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ShelfBookingConfirmation extends Notification
{
    use Queueable;

    public $user;
    public $shelfRental;
    public $productAddUrl;

    /**
     * Create a new notification instance.
     *
     * @param object $user
     * @param object $shelfRental
     * @param string $productAddUrl
     */
    public function __construct($user, $shelfRental, $productAddUrl)
    {
        $this->user = $user;
        $this->shelfRental = $shelfRental;
        $this->productAddUrl = $productAddUrl;
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
            ->subject('Deine Regalbuchung bei MiniFinds wurde bestätigt!')
            ->greeting('Hallo ' . $this->user->name . '!')
            ->line('Vielen Dank, dass du dich für MiniFinds entschieden hast!')
            ->line('Deine Buchung wurde erfolgreich bestätigt. Hier sind die Details:')
            ->line('**Regalnummer:** ' . $this->shelfRental->shelf->floor_number)
            ->line('**Buchungszeitraum:** ' . Carbon::parse($this->shelfRental->rental_start)->format('d.m.Y') . ' bis ' . Carbon::parse($this->shelfRental->rental_end)->format('d.m.Y'))
            ->line('**Gesamtpreis:** ' . number_format($this->shelfRental->total_price, 2, ',', '.') . ' €')
            ->action('Produkte hinzufügen', $this->productAddUrl)
            ->line('Falls du Fragen hast, kannst du uns jederzeit kontaktieren.')
            ->salutation('Liebe Grüße, dein MiniFinds-Team');
    }
}

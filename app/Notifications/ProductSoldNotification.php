<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProductSoldNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sale;

    /**
     * Konstruktor.
     *
     * @param $sale
     */
    public function __construct($sale)
    {
        $this->sale = $sale;
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
            ->subject('Produkt verkauft!')
            ->greeting('Hallo ' . $notifiable->name . ',')
            ->line('Herzlichen Glückwunsch! Eines deiner Produkte wurde verkauft.')
            ->line('Produkt: ' . $this->sale->product->name)
            ->line('Dein Erlös: ' . number_format($this->sale->net_sale_price, 2) . ' €')
            ->line('Verkaufsdatum: ' . $this->sale->date)
            ->action('Details ansehen', url('/shelf-rental/' . $this->sale->rental->id))
            ->line('Vielen Dank, dass du MiniFinds nutzt!')
            ->salutation('Liebe Grüße, dein MiniFinds-Team');
    }
}

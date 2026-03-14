<?php

namespace App\Notifications;

use App\Models\Mail as MailModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class MailNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $mailData; 

    /**
     * @param \App\Models\Mail|array $mailData
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $content = [];
        $files = collect();

        if ($this->mailData instanceof MailModel) {
            $this->mailData->loadMissing('files');
            $content = is_array($this->mailData->content) ? $this->mailData->content : [];
            $files = $this->mailData->files ?? collect();
        } elseif (is_array($this->mailData)) {
            $content = $this->mailData;
        }

        $subject = $content['subject'] ?? 'Nachricht';
        $greeting = $content['header'] ?? null;
        $body = $content['body'] ?? '';
        $link = $content['link'] ?? null;

        $mailMessage = (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($subject);

        if (! empty($greeting)) {
            $mailMessage->greeting($greeting);
        }

        $mailMessage->line($body);

        if (! empty($link)) {
            $mailMessage->action('Weiter', $link);
        }

        $mailMessage->salutation('Mit freundlichen Grüßen, dein Regulierungs-CHECK Team');

        foreach ($files as $file) {
            $disk = $file->disk ?? 'private';
            $path = $file->path;

            if (method_exists($mailMessage, 'attachFromStorageDisk')) {
                $mailMessage->attachFromStorageDisk($disk, $path, [
                    'as' => $file->name ?: basename($path),
                    'mime' => $file->mime_type ?: null,
                ]);
            } else {
                $absolute = Storage::disk($disk)->path($path);
                $mailMessage->attach($absolute, [
                    'as' => $file->name ?: basename($path),
                    'mime' => $file->mime_type ?: null,
                ]);
            }
        }

        return $mailMessage;
    }
}

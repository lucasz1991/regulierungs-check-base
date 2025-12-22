<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PublicFormNotification;
use App\Models\Setting;

class Premium extends Component
{
    public string $email = '';
    public string $role  = '';

    public function submit()
    {
        $data = $this->validate([
            'email' => ['required', 'email', 'max:255'],
            'role'  => ['nullable', 'string', 'max:255'],
        ]);

        // Damit dein PublicFormController die passende Flash-Message "abos" setzt:
        $payload = array_merge($data, [
            'form_type' => 'abos',
            'page'      => 'premium',
        ]);

        $recipient = Setting::where('key', 'contact_email')->value('value') ?? 'lucas@zacharias-net.de';

        Notification::route('mail', $recipient)
            ->notify(new PublicFormNotification($payload));

        Notification::route('mail', 'kontakt@regulierungs-check.de')
            ->notify(new PublicFormNotification($payload));

        // Felder leeren
        $this->reset('email', 'role');

        // Gleiche UX wie Controller: "redirect back" + success flash
        return redirect()->back()->with('success', 'Vielen Dank für deine unverbindliche Voranmeldung – wir melden uns bald bei dir!');
    }

    public function render()
    {
        return view('livewire.pages.premium')->layout('layouts.app');
    }
}

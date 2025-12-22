<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PublicFormNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Setting;


class PublicFormController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->except('_token');
        $formType = $data['form_type'] ?? 'unbekannt';
        
        // Empfänger dynamisch (optional)
        $recipient = Setting::where('key', 'contact_email')->value('value') ?? 'lucas@zacharias-net.de';

        // Sende Notification
        Notification::route('mail', $recipient )
            ->notify(new PublicFormNotification($data));
        Notification::route('mail', 'kontakt@regulierungs-check.de')
            ->notify(new PublicFormNotification($data));

        // Individuelle Flash-Nachricht oder Weiterleitung
        if ($formType === 'newsletter') {
            return redirect()->back()->with('success', 'Vielen Dank für deine Anmeldung zum Newsletter!');
        } elseif ($formType === 'kontakt') {
            return redirect()->back()->with('success', 'Vielen Dank für deine Nachricht – wir melden uns bald bei dir!');
        } elseif ($formType === 'abos') {
            return redirect()->back()->with('success', 'Vielen Dank für deine unverbindliche Voranmeldung – wir melden uns bald bei dir!');
        } else {
            return redirect()->back()->with('success', 'Vielen Dank für deine Eingabe!');
        }
    }

}

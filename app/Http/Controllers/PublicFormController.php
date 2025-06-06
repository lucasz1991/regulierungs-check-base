<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PublicFormNotification;
use Illuminate\Support\Facades\Notification;


class PublicFormController extends Controller
{
    public function handle(Request $request)
    {
        // Hole alle gesendeten Felder – außer sensible interne wie _token
        $data = $request->except('_token');
         // Versenden an eine bestimmte Adresse
        Notification::route('mail', 'lucas@zacharias-net.de')
            ->notify(new PublicFormNotification($data));
 
        // Protokolliere alle Felder (z. B. zum Testen oder Weiterverarbeiten)
        Log::info('Formular abgeschickt', $data);

        // Optional: Weiterleitung, Flash Message oder JSON-Response
        return redirect()->back()->with('success', 'Vielen Dank! Ihre Nachricht wurde übermittelt.');
    }
}

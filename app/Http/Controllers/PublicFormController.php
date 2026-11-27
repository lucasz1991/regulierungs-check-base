<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PublicFormNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Setting;
use App\Services\NewsletterSubscriptions;
use App\Services\PublicNewsletterSignup;
use Illuminate\Support\Facades\RateLimiter;


class PublicFormController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->except('_token');
        $formType = $data['form_type'] ?? 'unbekannt';
        if ($formType === 'newsletter') {
            $input = $request->validate(['email' => 'required|email:rfc|max:254']);
            $subscriptions = app(NewsletterSubscriptions::class);
            $account = $request->user();
            if ($account?->isActive() && $account->hasVerifiedEmail() &&
                NewsletterSubscriptions::email($account->email) === NewsletterSubscriptions::email($input['email'])) {
                $subscription = $subscriptions->forSource('user', $account->id);
                if (! $subscription->enabled || ! $subscription->consented_at) {
                    $subscriptions->update($subscription->id, $subscription->version, true, (bool) $subscription->neutral_greeting,
                        'verified_signup_form', $account->id, NewsletterSubscriptions::AGREEMENT);
                }
            } else {
                $key = 'public-newsletter-signup:'.hash('sha256', (string) $request->ip());
                abort_if(RateLimiter::tooManyAttempts($key, 8), 429, 'Bitte versuche es später erneut.');
                RateLimiter::hit($key, 3600);
                app(PublicNewsletterSignup::class)->request($input['email']);

                return back()->with('success', 'Vielen Dank! Bei einer neuen Anmeldung erhältst du einen Link zur Bestätigung deiner E-Mail-Adresse.');
            }

            return redirect()->back()->with('success', 'Vielen Dank für deine Anmeldung zum Newsletter!');
        }
        
        // Empfänger dynamisch (optional)
        $recipient = Setting::where('key', 'contact_email')->value('value') ?? 'lucas@zacharias-net.de';

        // Sende Notification
        Notification::route('mail', $recipient )
            ->notify(new PublicFormNotification($data));
        Notification::route('mail', 'kontakt@regulierungs-check.de')
            ->notify(new PublicFormNotification($data));

        // Individuelle Flash-Nachricht oder Weiterleitung
        if ($formType === 'kontakt') {
            return redirect()->back()->with('success', 'Vielen Dank für deine Nachricht – wir melden uns bald bei dir!');
        } elseif ($formType === 'abos') {
            return redirect()->back()->with('success', 'Vielen Dank für deine unverbindliche Voranmeldung – wir melden uns bald bei dir!');
        } else {
            return redirect()->back()->with('success', 'Vielen Dank für deine Eingabe!');
        }
    }

}

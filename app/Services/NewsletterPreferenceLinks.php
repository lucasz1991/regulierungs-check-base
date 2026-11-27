<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class NewsletterPreferenceLinks
{
    public function forRecipient(int $id, string $email): array
    {
        $row = DB::table('newsletter_subscriptions')->where('id', $id)
            ->where('email', NewsletterSubscriptions::email($email))->first();
        abort_unless($row, 404);
        $parameters = ['subscription' => $id, 'address' => $row->address_token];

        return [
            'settings_url' => URL::signedRoute('newsletter.preferences', $parameters),
            'unsubscribe_url' => URL::signedRoute('newsletter.unsubscribe', $parameters),
        ];
    }
}

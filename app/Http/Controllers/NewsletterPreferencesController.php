<?php

namespace App\Http\Controllers;

use App\Services\NewsletterSubscriptions;
use App\Services\PublicNewsletterSignup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NewsletterPreferencesController extends Controller
{
    public function confirm(Request $request, int $subscription, string $address, PublicNewsletterSignup $signup)
    {
        $submitted = $request->isMethod('post');
        if ($submitted) {
            $request->validate(['confirm' => 'required|accepted']);
        }
        $row = $signup->state($subscription, $address, (int) $request->query('v'), $submitted);
        if ($submitted) {
            return redirect()->to($request->fullUrl(), 303);
        }

        return view('newsletter.preferences', ['subscription' => $row, 'confirmation' => true, 'unsubscribe' => false]);
    }

    public function handle(Request $request, int $subscription, string $address, NewsletterSubscriptions $subscriptions)
    {
        $row = DB::table('newsletter_subscriptions')->where('id', $subscription)->where('address_token', $address)->first();
        abort_unless($row && $row->email, 404);
        if ($row->source_type !== 'public') {
            $owner = DB::table($row->source_type === 'user' ? 'users' : 'contacts')->where('id', $row->source_id)->first();
            abort_unless($owner && NewsletterSubscriptions::email($owner->email) === $row->email &&
                ($row->source_type !== 'user' || ! $owner->deleted_at), 404);
        } else {
            foreach (['users', 'contacts'] as $table) {
                abort_if(DB::table($table)->whereRaw('LOWER(TRIM(email)) = ?', [$row->email])->exists(), 404);
            }
        }
        $unsubscribe = str_starts_with($request->route()->getName(), 'newsletter.unsubscribe');
        if (! $unsubscribe && $row->source_type === 'user') {
            if (! $request->user()) {
                return redirect()->guest(route('login'));
            }
            abort_unless((int) $request->user()->id === (int) $row->source_id, 403);

            return redirect()->route('profile.show', ['tab' => 'newsletter']);
        }
        if ($request->isMethod('post')) {
            $rules = ['version' => 'required|integer|min:1'];
            if (! $unsubscribe) {
                $rules['action'] = ['required', Rule::in(['enable', 'disable', 'greeting'])];
                $rules['neutral'] = 'nullable|boolean';
            }
            $data = $request->validate($rules);
            $action = $unsubscribe ? 'disable' : $data['action'];
            $enabled = $action === 'greeting' ? (bool) $row->enabled : $action === 'enable';
            $neutral = $action === 'greeting' ? $request->boolean('neutral') : (bool) $row->neutral_greeting;
            $subscriptions->update($row->id, $data['version'], $enabled, $neutral, 'personal_link', null,
                $action === 'enable' ? NewsletterSubscriptions::AGREEMENT : null);

            return redirect()->to($request->fullUrl(), 303)->with('newsletter_notice', $enabled ? 'Einstellungen gespeichert.' : 'Du bist vom Newsletter abgemeldet.');
        }

        return response()->view('newsletter.preferences', ['subscription' => $row, 'unsubscribe' => $unsubscribe])
            ->header('Cache-Control', 'no-store, private')->header('Referrer-Policy', 'no-referrer')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}

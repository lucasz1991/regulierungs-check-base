<?php

namespace Tests\Feature;

use App\Livewire\Profile\NewsletterPreference;
use App\Models\User;
use App\Notifications\PublicNewsletterConfirmation;
use App\Services\NewsletterPreferenceLinks;
use App\Services\NewsletterSubscriptions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class NewsletterPreferencesTest extends TestCase
{
    private NewsletterSubscriptions $subscriptions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());
        $this->withoutMiddleware(\App\Http\Middleware\LogActivity::class);
        DB::statement('CREATE TABLE settings (id INTEGER PRIMARY KEY, key TEXT, value TEXT, updated_at TEXT)');
        DB::statement('CREATE TABLE users (id INTEGER PRIMARY KEY, name TEXT, email TEXT, status INTEGER, email_verified_at TEXT, deleted_at TEXT)');
        DB::statement('CREATE TABLE contacts (id INTEGER PRIMARY KEY, name TEXT, email TEXT, mail_permission TEXT)');
        DB::statement('CREATE TABLE customers (id INTEGER PRIMARY KEY, user_id INTEGER, first_name TEXT, deleted_at TEXT)');
        (require database_path('migrations/2026_11_27_090000_create_newsletter_subscriptions.php'))->up();
        DB::table('users')->insert(['id' => 1, 'name' => 'Alex Winter', 'email' => 'alex@example.test', 'status' => 1, 'email_verified_at' => now()]);
        DB::table('customers')->insert(['user_id' => 1, 'first_name' => 'Alex']);
        DB::table('contacts')->insert(['id' => 8, 'name' => 'Kim Sommer', 'email' => 'kim@example.test', 'mail_permission' => 'allowed']);
        $this->subscriptions = app(NewsletterSubscriptions::class);
        $this->subscriptions->syncSources();
    }

    public function test_existing_people_have_active_preference_only_until_evidence_exists(): void
    {
        Notification::fake();
        $service = $this->subscriptions;
        $service->syncSources();
        $this->assertSame(2, DB::table('newsletter_subscriptions')->where('enabled', true)->count());
        $this->assertSame(0, $service->eligibleQuery()->count());
        $user = User::findOrFail(1);
        Livewire::actingAs($user)->test(NewsletterPreference::class)->assertSee('Newsletter abonnieren')
            ->call('change', 'allow')->assertHasNoErrors()->assertSee('Versand für diese Adresse freigegeben.');
        $s = $service->forSource('user', 1);
        $this->assertSame('Alex', $service->recipient($s->id, $user->email)['name']);
        $service->update($s->id, $s->version, true, true, 'own_profile', 1);
        $this->assertNull($service->recipient($s->id, $user->email)['name']);
        DB::table('users')->where('id', 1)->update(['email' => 'neu@example.test']);
        $this->assertNull($service->recipient($s->id, $s->email));
        $service->syncSources();
        $this->assertNull($service->forSource('user', 1)->consented_at);
        Notification::assertNothingSent();
    }

    public function test_signed_contact_preference_and_unsubscribe_read_only_on_get_require_current_address(): void
    {
        $s = $this->subscriptions->forSource('contact', 8);
        $this->subscriptions->update($s->id, $s->version, true, false, 'admin_profile', 17, 'Anmeldung mit Nachweis.');
        $s = $this->subscriptions->forSource('contact', 8);
        $links = app(NewsletterPreferenceLinks::class)->forRecipient($s->id, $s->email);
        $this->get($links['settings_url'])->assertOk()->assertSee('Newsletter-Einstellungen');
        $this->get($links['unsubscribe_url'])->assertOk()->assertSee('Newsletter abbestellen');
        $this->assertSame('Kim Sommer', $this->subscriptions->recipient($s->id, $s->email)['name']);
        $this->get($links['unsubscribe_url'].'x')->assertForbidden();
        $this->post($links['unsubscribe_url'], ['version' => $s->version])->assertRedirect();
        $this->assertNull($this->subscriptions->recipient($s->id, $s->email));
        DB::table('contacts')->where('id', 8)->update(['email' => 'new@example.test']);
        $this->getJson($links['settings_url'])->assertNotFound();
        $this->assertDatabaseHas('newsletter_suppressions', ['email' => 'kim@example.test']);
    }

    public function test_duplicate_blocked_and_stale_updates_do_not_send_or_restore_permission(): void
    {
        $s = $this->subscriptions->forSource('user', 1);
        $this->subscriptions->update($s->id, $s->version, true, false, 'own_profile', 1, NewsletterSubscriptions::AGREEMENT);
        DB::table('contacts')->insert(['id' => 9, 'name' => 'Zweites Profil', 'email' => 'Alex@example.test', 'mail_permission' => 'blocked']);
        $this->assertNull($this->subscriptions->recipient($s->id, 'alex@example.test'));
        $this->subscriptions->syncSources();
        DB::table('contacts')->where('id', 9)->delete();
        $this->assertNull($this->subscriptions->recipient($s->id, 'alex@example.test'));
        $this->expectException(ValidationException::class);
        $this->subscriptions->update($s->id, $s->version, true, false, 'own_profile', 1, NewsletterSubscriptions::AGREEMENT);
    }

    public function test_anonymous_requests_for_existing_users_contacts_and_suppressed_addresses_do_not_send_an_invitation(): void
    {
        Notification::fake();
        $s = $this->subscriptions->forSource('user', 1);
        $this->subscriptions->update($s->id, $s->version, false, false, 'own_profile', 1);
        DB::table('newsletter_suppressions')->insert(['email' => 'neu@example.test', 'created_at' => now()]);
        foreach (['alex@example.test', 'Kim@example.test', 'neu@example.test'] as $email) {
            $this->post(route('form.submit'), ['form_type' => 'newsletter', 'email' => $email])->assertRedirect();
        }
        $this->assertSame(2, DB::table('newsletter_subscriptions')->count());
        $this->assertSame(0, $this->subscriptions->eligibleQuery()->count());
        $this->assertDatabaseHas('newsletter_subscriptions', ['source_type' => 'user', 'enabled' => false]);
        $this->actingAs(User::findOrFail(1))->post(route('form.submit'), ['form_type' => 'newsletter', 'email' => 'alex@example.test'])->assertRedirect();
        $this->assertDatabaseHas('newsletter_subscriptions', ['source_type' => 'user', 'consent_source' => 'verified_signup_form', 'enabled' => true]);
        $this->assertSame(1, $this->subscriptions->eligibleQuery()->count());
        Notification::assertNothingSent();
    }

    public function test_public_signup_requires_a_signed_unexpired_link_and_a_deliberate_post_only_once(): void
    {
        Notification::fake();
        $form = ['form_type' => 'newsletter', 'email' => 'Test@example.test'];
        $this->post(route('form.submit'), $form)->assertRedirect();
        $this->post(route('form.submit'), $form)->assertRedirect();
        Notification::assertSentOnDemandTimes(PublicNewsletterConfirmation::class, 1);
        $link = '';
        Notification::assertSentOnDemand(PublicNewsletterConfirmation::class, function ($notice, $channels, $recipient) use (&$link) {
            $link = $notice->toMail($recipient)->actionUrl;

            return $recipient->routeNotificationFor('mail') === 'test@example.test';
        });
        $this->get($link)->assertOk()->assertSee('Newsletter-Anmeldung bestätigen');
        $this->post($link)->assertSessionHasErrors('confirm');
        $this->get($link.'x')->assertForbidden();
        $this->assertSame(0, $this->subscriptions->eligibleQuery()->count());
        $this->travel(25)->hours();
        $this->post($link, ['confirm' => 1])->assertForbidden();
        $this->post(route('form.submit'), $form)->assertRedirect();
        Notification::assertSentOnDemandTimes(PublicNewsletterConfirmation::class, 2);
        Notification::assertSentOnDemand(PublicNewsletterConfirmation::class, function ($notice, $channels, $recipient) use (&$link) {
            $next = $notice->toMail($recipient)->actionUrl;
            if ($next === $link) {
                return false;
            }
            $link = $next;

            return true;
        });
        $this->post($link, ['confirm' => 1])->assertStatus(303);
        $this->post($link, ['confirm' => 1])->assertStatus(303);
        $this->get($link)->assertOk()->assertSee('Du bist angemeldet');
        $this->post(route('form.submit'), $form)->assertRedirect();
        Notification::assertSentOnDemandTimes(PublicNewsletterConfirmation::class, 2);
        $this->assertSame(1, $this->subscriptions->eligibleQuery()->count());
        $this->assertSame(1, DB::table('newsletter_preference_events')->where('event', 'consent')->count());
    }

    public function test_signup_worker_delivers_the_confirmation_only_to_a_still_unclaimed_public_address(): void
    {
        (require database_path('migrations/2025_01_20_051900_create_jobs_table.php'))->up();
        config(['mail.default' => 'array', 'mail.from.address' => 'sender@example.test']);
        foreach (['first', 'suppressed', 'claimed'] as $name) {
            $this->post(route('form.submit'), ['form_type' => 'newsletter', 'email' => $name.'@example.test'])->assertRedirect();
        }
        $this->assertDatabaseCount('jobs', 3);
        DB::table('newsletter_suppressions')->insert(['email' => 'suppressed@example.test', 'created_at' => now()]);
        DB::table('contacts')->insert(['id' => 51, 'name' => 'Neuer Kontakt', 'email' => 'claimed@example.test', 'mail_permission' => 'allowed']);
        for ($i = 0; $i < 3; $i++) {
            $this->assertSame(0, Artisan::call('queue:work', ['connection' => 'database', '--queue' => 'kundenmail', '--once' => true, '--sleep' => 0]));
        }
        $this->assertDatabaseCount('jobs', 0);
        $messages = Mail::mailer('array')->getSymfonyTransport()->messages();
        $this->assertCount(1, $messages);
        $mail = $messages->first()->getOriginalMessage();
        $this->assertSame('first@example.test', $mail->getTo()[0]->getAddress());
        $this->assertStringContainsString('/newsletter/bestaetigen/', $mail->getTextBody());
        $this->assertSame(0, $this->subscriptions->eligibleQuery()->count());
    }

    public function test_public_signup_has_a_per_ip_request_limit(): void
    {
        Notification::fake();
        $form = ['form_type' => 'newsletter', 'email' => 'limited@example.test'];
        for ($i = 0; $i < 8; $i++) {
            $this->post(route('form.submit'), $form)->assertRedirect();
        }
        $this->post(route('form.submit'), $form)->assertStatus(429);
        Notification::assertSentOnDemandTimes(PublicNewsletterConfirmation::class, 1);
    }

    public function test_previously_issued_confirmation_cannot_override_a_later_suppression_owner_or_address_token(): void
    {
        Notification::fake();
        $this->post(route('form.submit'), ['form_type' => 'newsletter', 'email' => 'owned@example.test'])->assertRedirect();
        $recipient = Notification::route('mail', 'owned@example.test');
        $notice = Notification::sent($recipient, PublicNewsletterConfirmation::class)->sole();
        $link = $notice->toMail($recipient)->actionUrl;
        DB::table('newsletter_suppressions')->insert(['email' => 'owned@example.test', 'created_at' => now()]);
        $this->postJson($link, ['confirm' => 1])->assertNotFound();
        $this->assertDatabaseHas('newsletter_suppressions', ['email' => 'owned@example.test']);
        DB::table('newsletter_suppressions')->where('email', 'owned@example.test')->delete();
        DB::table('users')->insert(['id' => 23, 'name' => 'Neues Konto', 'email' => 'Owned@example.test']);
        $this->postJson($link, ['confirm' => 1])->assertNotFound();
        DB::table('users')->where('id', 23)->delete();
        DB::table('newsletter_subscriptions')->where('email', 'owned@example.test')->update(['address_token' => (string) \Illuminate\Support\Str::uuid()]);
        $this->postJson($link, ['confirm' => 1])->assertNotFound();
        $this->assertSame(0, DB::table('newsletter_preference_events')->where('event', 'consent')->count());
    }
}

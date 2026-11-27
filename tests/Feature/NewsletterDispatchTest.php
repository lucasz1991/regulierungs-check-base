<?php

namespace Tests\Feature;

use App\Jobs\DeliverNewsletter;
use App\Services\NewsletterDispatch;
use App\Services\NewsletterSubscriptions;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NewsletterDispatchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(str_repeat('a', 32)), 'mail.default' => 'array', 'mail.from.address' => 'sender@example.test']);
        foreach (['000100_create_newsletter_campaigns_table', '000200_create_newsletter_deliveries_table', '090000_create_newsletter_subscriptions'] as $migration) {
            (require database_path('migrations/2026_11_27_'.$migration.'.php'))->up();
        }
        DB::statement('CREATE TABLE users (id INTEGER PRIMARY KEY, email TEXT, name TEXT, status BOOLEAN, deleted_at TEXT, email_verified_at TEXT)');
        DB::statement('CREATE TABLE contacts (id INTEGER PRIMARY KEY, email TEXT, name TEXT, mail_permission TEXT)');
        DB::statement('CREATE TABLE settings (id INTEGER PRIMARY KEY, type TEXT, key TEXT, value TEXT)');
        DB::statement('CREATE TABLE posts (id INTEGER PRIMARY KEY, type TEXT, slug TEXT, published BOOLEAN, published_at TEXT, deleted_at TEXT)');
        DB::table('contacts')->insert(['id' => 7, 'email' => 'kim@example.test', 'name' => 'Kim Sommer', 'mail_permission' => 'allowed']);
    }

    private function scheduled(?int $postId = null): array
    {
        $sub = app(NewsletterSubscriptions::class)->forSource('contact', 7);
        DB::table('newsletter_subscriptions')->where('id', $sub->id)->update(['consented_at' => now(), 'consent_source' => 'test']);
        $id = DB::table('newsletter_campaigns')->insertGetId(['post_id' => $postId, 'name' => 'Test', 'subject' => 'Ausgabe', 'body' => 'Eigener Teasertext.',
            'status' => 'scheduled', 'scheduled_at' => now('UTC')->subMinute(), 'article_title' => $postId ? 'Beitragstitel' : null]);
        $delivery = DB::table('newsletter_deliveries')->insertGetId(['campaign_id' => $id, 'subscription_id' => $sub->id, 'contact_id' => 7, 'email' => 'kim@example.test']);

        return [$id, $delivery, $sub->id];
    }

    public function test_database_worker_sends_personal_greeting_and_links_only_once_even_if_job_is_replayed(): void
    {
        (require database_path('migrations/2025_01_20_051900_create_jobs_table.php'))->up();
        [$id, $delivery] = $this->scheduled();
        $dispatch = app(NewsletterDispatch::class);
        $dispatch->queueDue();
        $dispatch->queueDue();
        $this->assertDatabaseCount('jobs', 1);
        $this->assertDatabaseHas('jobs', ['queue' => 'kundenmail', 'reserved_at' => null]);

        $worker = ['connection' => 'database', '--queue' => 'kundenmail', '--once' => true, '--sleep' => 0];
        $this->assertSame(0, Artisan::call('queue:work', $worker));
        $this->assertDatabaseCount('jobs', 0);
        $this->assertDatabaseHas('newsletter_deliveries', ['id' => $delivery, 'status' => 'sent']);
        DeliverNewsletter::dispatch($id, $delivery)->onConnection('database')->onQueue('kundenmail');
        $this->assertDatabaseCount('jobs', 1);
        $this->assertSame(0, Artisan::call('queue:work', $worker));
        $this->assertDatabaseCount('jobs', 0);
        $dispatch->queueDue();
        $messages = Mail::mailer('array')->getSymfonyTransport()->messages();
        $this->assertCount(1, $messages);
        $mail = $messages->first()->getOriginalMessage();
        $this->assertSame('kim@example.test', $mail->getTo()[0]->getAddress());
        foreach ([$mail->getHtmlBody(), $mail->getTextBody()] as $rendered) {
            $this->assertStringContainsString('Hallo Kim Sommer,', $rendered);
            $this->assertStringContainsString('Eigener Teasertext.', $rendered);
            $this->assertStringContainsString('/newsletter/einstellungen/', $rendered);
            $this->assertStringContainsString('/newsletter/abmelden/', $rendered);
        }
        $this->assertDatabaseHas('newsletter_campaigns', ['id' => $id, 'status' => 'sent']);
        $this->assertDatabaseHas('newsletter_deliveries', ['id' => $delivery, 'status' => 'sent']);
    }

    public function test_positive_acknowledgement_resolves_a_handoff_marked_uncertain_while_sending(): void
    {
        Queue::fake();
        [$id, $delivery] = $this->scheduled();
        $dispatch = app(NewsletterDispatch::class);
        $dispatch->queueDue();
        $duringHandoff = null;
        Event::listen(MessageSent::class, function () use ($dispatch, $delivery, &$duringHandoff) {
            $row = DB::table('newsletter_deliveries')->where('id', $delivery);
            $row->update(['updated_at' => now('UTC')->subMinutes(16)]);
            $dispatch->queueDue();
            $duringHandoff = $row->value('status');
        });
        $dispatch->deliver($id, $delivery);
        $this->assertSame('uncertain', $duringHandoff);
        $this->assertDatabaseHas('newsletter_deliveries', ['id' => $delivery, 'status' => 'sent']);
        $this->assertCount(1, Mail::mailer('array')->getSymfonyTransport()->messages());
    }

    public function test_later_opt_out_skips_a_previously_queued_recipient(): void
    {
        Queue::fake();
        [$id,$delivery,$sub] = $this->scheduled();
        $dispatch = app(NewsletterDispatch::class);
        $dispatch->queueDue();
        DB::table('newsletter_subscriptions')->where('id', $sub)->update(['enabled' => false]);
        $dispatch->deliver($id, $delivery);
        $this->assertDatabaseHas('newsletter_deliveries', ['id' => $delivery, 'status' => 'skipped']);
        $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
    }

    public function test_article_must_still_be_visible_when_the_job_runs_and_old_slug_is_resolved_by_id(): void
    {
        Queue::fake();
        [$id,$delivery] = $this->scheduled(42);
        DB::table('settings')->insert(['type' => 'webcontent', 'key' => 'news_enabled', 'value' => 'true']);
        DB::table('posts')->insert(['id' => 42, 'type' => 'news', 'slug' => 'neuer-titel', 'published' => 1, 'published_at' => now()->subDay()]);
        $dispatch = app(NewsletterDispatch::class);
        $dispatch->queueDue();
        $this->get('/news/lesen/42')->assertRedirect(route('news.show', 'neuer-titel'));
        DB::table('posts')->where('id', 42)->update(['published' => 0]);
        $dispatch->deliver($id, $delivery);
        $this->assertDatabaseHas('newsletter_campaigns', ['id' => $id, 'status' => 'blocked']);
        $this->assertDatabaseHas('newsletter_deliveries', ['id' => $delivery, 'status' => 'pending']);
        $this->assertCount(0, Mail::mailer('array')->getSymfonyTransport()->messages());
        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->get('/news/lesen/42');
    }

    public function test_future_campaign_and_uncertain_handoff_are_not_replayed(): void
    {
        Queue::fake();
        [$id,$delivery] = $this->scheduled();
        DB::table('newsletter_campaigns')->where('id', $id)->update(['scheduled_at' => now('UTC')->addDay()]);
        $dispatch = app(NewsletterDispatch::class);
        $dispatch->queueDue();
        Queue::assertNothingPushed();
        DB::table('newsletter_campaigns')->where('id', $id)->update(['status' => 'sending']);
        DB::table('newsletter_deliveries')->where('id', $delivery)->update(['status' => 'processing', 'updated_at' => now('UTC')->subHour()]);
        $dispatch->queueDue();
        $dispatch->deliver($id, $delivery);
        $this->assertDatabaseHas('newsletter_deliveries', ['id' => $delivery, 'status' => 'uncertain']);
        Queue::assertNothingPushed();
    }

    public function test_finished_run_closes_even_if_its_news_was_removed_after_delivery(): void
    {
        Queue::fake();
        [$id,$delivery] = $this->scheduled(42);
        DB::table('newsletter_campaigns')->where('id', $id)->update(['status' => 'sending']);
        DB::table('newsletter_deliveries')->where('id', $delivery)->update(['status' => 'sent']);
        app(NewsletterDispatch::class)->queueDue();
        $this->assertDatabaseHas('newsletter_campaigns',['id' => $id, 'status' => 'sent']);
        Queue::assertNothingPushed();
    }
}

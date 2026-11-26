<?php

namespace Tests\Feature;

use App\Jobs\ProcessMailJob;
use App\Models\Mail;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail as Transport;
use Tests\TestCase;

class CustomerMailDeliveryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['mail.default' => 'array', 'mail.from.address' => 'team@example.test']);
        DB::statement('CREATE TABLE mails (id INTEGER PRIMARY KEY, type TEXT, status INTEGER, content TEXT, recipients TEXT, created_at TEXT, updated_at TEXT)');
        DB::statement('CREATE TABLE users (id INTEGER PRIMARY KEY, email TEXT)');
        DB::statement('CREATE TABLE files (id INTEGER PRIMARY KEY, fileable_type TEXT, fileable_id INTEGER)');
        DB::statement('CREATE TABLE messages (id INTEGER PRIMARY KEY, from_user INTEGER, to_user INTEGER, subject TEXT, message TEXT, status INTEGER, created_at TEXT, updated_at TEXT)');
        (require database_path('migrations/2026_11_25_120000_create_contacts_and_mail_dispatch_key.php'))->up();
        DB::table('users')->insert(['id' => 12, 'email' => 'person@example.test']);
    }

    public static function channels(): array
    {
        return [['mail', 0, 1], ['message', 1, 0], ['both', 1, 1]];
    }

    /** @dataProvider channels */
    public function test_each_channel_delivers_once_even_on_job_replay(string $type, int $inbox, int $emails): void
    {
        $mail = $this->order($type, ['user_id' => 12, 'email' => 'person@example.test']);
        $this->runDelivery($mail);
        $this->runDelivery($mail);
        $this->assertSame($inbox, DB::table('messages')->count());
        $messages = Transport::mailer('array')->getSymfonyTransport()->messages();
        $this->assertCount($emails, $messages);
        if ($emails) {
            $this->assertSame('Anfrage', $messages->first()->getOriginalMessage()->getSubject());
            $this->assertStringContainsString('Hallo Alex', $messages->first()->getOriginalMessage()->getHtmlBody());
        }
        $this->assertSame('sent', $mail->fresh()->recipients[0]['state']);
    }

    public static function actionLabels(): array
    {
        $link = 'https://example.test/unterlagen';

        return [
            'custom' => [['link' => $link, 'button_label' => '  Schäden & <Details>  '], 'Schäden & <Details>'],
            'legacy order' => [['link' => $link], 'Weiter'],
            'blank label' => [['link' => $link, 'button_label' => " \t "], 'Weiter'],
            'numeric text' => [['link' => $link, 'button_label' => '0'], '0'],
            'no link' => [['button_label' => 'Unterlagen ansehen'], null],
        ];
    }

    /** @dataProvider actionLabels */
    public function test_worker_delivers_the_saved_action_in_html_and_plain_text(array $action, ?string $expectedLabel): void
    {
        $mail = $this->order('mail', ['user_id' => 12, 'email' => 'person@example.test'], $action);
        $this->runDelivery($mail);
        $messages = Transport::mailer('array')->getSymfonyTransport()->messages();
        $this->assertCount(1, $messages);
        $message = $messages->first()->getOriginalMessage();
        $html = $message->getHtmlBody();
        $text = $message->getTextBody();
        if ($expectedLabel === null) {
            $this->assertStringNotContainsString($action['button_label'], $html);
            $this->assertStringNotContainsString($action['button_label'], $text);
            $this->assertStringNotContainsString('Falls der Button', $html);
        } else {
            $this->assertSame(2, substr_count($html, 'href="'.$action['link'].'"'));
            $this->assertStringContainsString('>'.e($expectedLabel), $html);
            $this->assertMatchesRegularExpression('~^'.preg_quote($expectedLabel, '~').'\R+'.preg_quote($action['link'], '~').'$~m', $text);
        }
    }

    public function test_customer_without_account_receives_mail_and_revoked_contact_is_skipped(): void
    {
        $contact = DB::table('contacts')->insertGetId(['name' => 'Team', 'email' => 'external@example.test', 'mail_permission' => 'allowed']);
        foreach (['sent', 'skipped'] as $state) {
            $mail = $this->order('mail', ['contact_id' => $contact, 'email' => 'external@example.test']);
            $this->runDelivery($mail);
            $this->assertSame($state, $mail->fresh()->recipients[0]['state']);
            DB::table('contacts')->where('id', $contact)->update(['mail_permission' => 'blocked']);
        }
        $this->assertCount(1, Transport::mailer('array')->getSymfonyTransport()->messages());
        $this->assertSame(0, DB::table('messages')->count());
    }

    public function test_failed_email_in_combined_mode_can_retry_without_duplicating_the_inbox_message(): void
    {
        $mail = $this->order('both', ['user_id' => 12, 'email' => 'person@example.test']);
        $first = true;
        Event::listen(MessageSending::class, function () use (&$first) {
            if ($first) {
                $first = false;
                throw new \RuntimeException('Simulierter Transportfehler');
            }
        });
        $this->runDelivery($mail);
        $recipients = $mail->fresh()->recipients;
        $this->assertSame('failed', $recipients[0]['state']);
        $this->assertTrue($recipients[0]['message_sent']);
        $recipients[0]['state'] = 'pending';
        $mail->update(['recipients' => $recipients]);
        $this->runDelivery($mail);
        $this->assertSame('sent', $mail->fresh()->recipients[0]['state']);
        $this->assertSame(1, DB::table('messages')->count());
        $this->assertCount(1, Transport::mailer('array')->getSymfonyTransport()->messages());
    }

    private function order(string $type, array $recipient, array $content = []): Mail
    {
        return Mail::withoutEvents(fn () => Mail::create(['type' => $type, 'status' => false,
            'content' => $content + ['subject' => 'Anfrage', 'header' => 'Hallo Alex', 'body' => 'Nachricht', 'link' => ''],
            'recipients' => [array_merge($recipient, ['state' => 'pending', 'status' => false])]]));
    }

    private function runDelivery(Mail $mail): void
    {
        $job = new ProcessMailJob($mail);
        $job->recipientIndex = 0;
        $job->handle();
    }
}

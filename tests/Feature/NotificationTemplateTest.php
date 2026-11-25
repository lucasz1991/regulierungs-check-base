<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\MailNotification;
use Illuminate\Mail\Markdown;
use Tests\TestCase;

class NotificationTemplateTest extends TestCase
{
    public function test_verification_action_and_signature_appear_in_both_parts(): void
    {
        $user = (new User)->forceFill(['id' => 17, 'email' => 'alex@example.test']);
        $message = (new CustomVerifyEmail)->toMail($user);
        $html = (string) $message->render();
        $text = (string) app(Markdown::class)->renderText($message->markdown, $message->data());

        $this->assertStringContainsString('#f9b029', $html);
        $this->assertStringContainsString('/site-images/logo-xs.png', $html);
        foreach (['envelope', 'globe', 'location'] as $icon) {
            $path = '/site-images/mail/'.$icon.'.png';
            $this->assertStringContainsString('https://www.regulierungs-check.de'.$path, $html);
            $this->assertSame([48, 48], array_slice(getimagesize(public_path($path)), 0, 2));
            $this->assertStringNotContainsString($path, $text);
        }
        $this->assertSame(2, substr_count($html, 'href="'.e($message->actionUrl).'"'));
        $this->assertStringContainsString($message->actionUrl, $text);
        foreach ([$html, $text] as $part) {
            $this->assertStringContainsString($message->greeting, $part);
            $this->assertStringContainsString($message->actionText, $part);
            $this->assertStringContainsString($message->outroLines[0], $part);
            $this->assertStringContainsString($message->salutation, $part);
            $this->assertSame(1, substr_count($part, 'Regulierungs-Check RCQ GmbH'));
        }
        $this->assertStringNotContainsString('<table', $text);
    }

    public function test_customer_mail_without_action_has_no_button_and_escapes_user_content(): void
    {
        $message = (new MailNotification(['subject' => 'Eine Rückfrage', 'body' => 'Bitte <b>antworten</b>.']))
            ->toMail(new User);
        $html = (string) $message->render();
        $text = (string) app(Markdown::class)->renderText($message->markdown, $message->data());

        $this->assertStringContainsString('<title>Eine Rückfrage</title>', $html);
        $this->assertStringContainsString('Bitte &lt;b&gt;antworten&lt;/b&gt;.', $html);
        $this->assertStringContainsString('Bitte <b>antworten</b>.', $text);
        $this->assertStringNotContainsString('Falls der Button', $html);
        $this->assertStringNotContainsString('Versicherer entdecken', $html);
        $this->assertStringNotContainsString('Hallo [Vorname]', $html);
    }
}

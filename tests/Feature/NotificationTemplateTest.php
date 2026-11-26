<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\MailNotification;
use DOMDocument;
use DOMXPath;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class NotificationTemplateTest extends TestCase
{
    public function test_legacy_worker_salutation_is_normalized_without_replacing_custom_signoffs(): void
    {
        foreach ([
            'Mit freundlichen Grüßen, dein Regulierungs-CHECK Team' => 'Viele Grüße',
            'Herzliche Grüße von Alex' => 'Herzliche Grüße von Alex',
            'Mit freundlichen Grüßen, dein anderes Team' => 'Mit freundlichen Grüßen, dein anderes Team',
        ] as $salutation => $expected) {
            $message = (new MailMessage)->subject('Rückfrage')->greeting('Hallo Alex')->line('Unsere Nachricht.')->salutation($salutation);
            $parts = [
                (string) $message->render(),
                (string) app(Markdown::class)->renderText($message->markdown, $message->data()),
            ];
            foreach ($parts as $part) {
                $this->assertStringContainsString($expected, $part);
                $this->assertStringContainsString('Unsere Nachricht.', $part);
                $this->assertStringNotContainsString('Mit freundlichen Grüßen, dein Regulierungs-CHECK Team', $part);
            }
        }
    }

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

    public function test_long_customer_mail_preserves_all_paragraphs_and_links_in_both_parts(): void
    {
        $paragraphs = array_map(fn ($i) => 'Absatz '.$i.': '.rtrim(str_repeat('Schadenerfahrungen schaffen Transparenz. ', 12)), range(1, 25));
        $message = (new MailNotification([
            'subject' => 'Lange Nachricht',
            'header' => 'Hallo Alex,',
            'body' => implode("\n\n", $paragraphs),
            'link' => 'https://example.test/weiter?eins=1&zwei=2',
        ]))->toMail(new User);
        $html = (string) $message->render();
        $text = (string) app(Markdown::class)->renderText($message->markdown, $message->data());

        foreach ([$html, $text] as $part) {
            foreach ($paragraphs as $paragraph) {
                $this->assertStringContainsString($paragraph, $part);
            }
            $this->assertStringContainsString('Hallo Alex,', $part);
            $this->assertStringContainsString('Viele Grüße', $part);
            $this->assertStringNotContainsString('dein Regulierungs-CHECK Team', $part);
            $this->assertSame(1, substr_count($part, 'Regulierungs-Check RCQ GmbH'));
        }
        $this->assertSame(2, substr_count($html, 'href="'.e($message->actionUrl).'"'));
        $this->assertStringContainsString($message->actionUrl, $text);
    }

    public function test_short_mail_background_follows_content_height_and_signature_keeps_three_columns(): void
    {
        $message = (new MailNotification(['subject' => 'Test', 'body' => 'Eine kurze Nachricht.']))->toMail(new User);
        $html = (string) $message->render();
        $text = (string) app(Markdown::class)->renderText($message->markdown, $message->data());
        $backgroundPath = 'site-images/mail/background-curves.png';
        $backgroundUrl = 'https://www.regulierungs-check.de/'.$backgroundPath;

        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        try {
            $document->loadHTML('<?xml encoding="UTF-8">'.$html);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
        $xpath = new DOMXPath($document);

        $this->assertSame(IMAGETYPE_PNG, getimagesize(public_path($backgroundPath))[2]);
        $this->assertStringNotContainsString($backgroundUrl, $text);

        $canvas = $xpath->query('//body/table[@width="100%"]/tr/td[@background]');
        $this->assertSame(1, $canvas->length);
        $this->assertSame($backgroundUrl, $canvas->item(0)->getAttribute('background'));
        $this->assertStringContainsString($backgroundUrl, $canvas->item(0)->getAttribute('style'));
        $this->assertSame('center', $canvas->item(0)->getAttribute('align'));
        $outlookFrame = $xpath->query('.//comment()[contains(., "mso") and contains(., \'width="760"\')]', $canvas->item(0));
        $this->assertSame(1, $outlookFrame->length);
        $frame = $xpath->query('.//table[@class="mail-frame"]', $canvas->item(0));
        $this->assertSame(1, $frame->length);
        $this->assertMatchesRegularExpression('/max-width:\s*760px\b/i', $frame->item(0)->getAttribute('style'));
        $this->assertSame(0, $xpath->query('.//*[@background]', $frame->item(0))->length);

        // Classic Outlook must end the artwork at the same footer as HTML mail clients.
        $this->assertStringNotContainsString('<v:background', $html);
        $shape = $xpath->query('.//comment()[contains(., "<v:rect")]', $canvas->item(0));
        $this->assertSame(1, $shape->length);
        $vml = $shape->item(0)->nodeValue;
        $this->assertStringContainsString('src="'.$backgroundUrl.'"', $vml);
        $this->assertMatchesRegularExpression('/mso-width-percent:\s*(?:9\d\d|1000)\b/i', $vml);
        $this->assertMatchesRegularExpression('/mso-fit-shape-to-text:\s*true/i', $vml);
        $this->assertDoesNotMatchRegularExpression('/\bheight\s*[:=]\s*["\']?\d/i', $vml);
        $this->assertSame(1, $xpath->query('.//comment()[contains(., "</v:rect>")]', $canvas->item(0))->length);

        foreach ($xpath->query('ancestor-or-self::*', $frame->item(0)) as $wrapper) {
            $this->assertContains($wrapper->getAttribute('height'), ['', 'auto'], $wrapper->tagName);
            $this->assertDoesNotMatchRegularExpression(
                '/(?:^|;)\s*(?:min-)?height\s*:\s*[1-9]/i',
                $wrapper->getAttribute('style'),
                $wrapper->tagName.' must fit the message even when the reading pane is taller.',
            );
        }

        $row = $xpath->query('//td[contains(concat(" ", normalize-space(@class), " "), " signature-brand ")]/parent::tr');
        $this->assertSame(1, $row->length);
        $cells = $xpath->query('./td', $row->item(0));
        $this->assertSame(3, $cells->length);
        $this->assertStringContainsString('signature-contacts', $cells->item(1)->getAttribute('class'));
        $this->assertStringContainsString('signature-address', $cells->item(2)->getAttribute('class'));
        $this->assertStringContainsString('info@regulierungs-check.de', $cells->item(1)->textContent);
        $this->assertStringNotContainsString('Oldesloer', $cells->item(1)->textContent);
        $this->assertStringContainsString('Oldesloer Str. 63', $cells->item(2)->textContent);
        $this->assertStringContainsString('22457 Hamburg', $cells->item(2)->textContent);
    }
}

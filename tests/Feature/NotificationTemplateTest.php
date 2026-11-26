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
        $xpath = $this->parseMailHtml($html);
        $left = $xpath->query('//body/table[@width="100%"]/tr/td[@background]');
        $this->assertSame(1, $left->length);
        $right = $xpath->query('.//table[@width="100%"]/tr/td[@background]', $left->item(0));
        $this->assertSame(1, $right->length);

        foreach (['left' => 'top', 'right' => 'bottom'] as $side => $vertical) {
            $path = "site-images/mail/background-circles-{$side}.png";
            $url = 'https://www.regulierungs-check.de/'.$path;
            $dimensions = getimagesize(public_path($path));
            $this->assertSame([320, 512, IMAGETYPE_PNG], array_slice($dimensions, 0, 3));
            $cell = $side === 'left' ? $left->item(0) : $right->item(0);
            $this->assertSame($url, $cell->getAttribute('background'));
            $style = $cell->getAttribute('style');
            $this->assertStringContainsString($url, $style);
            $this->assertMatchesRegularExpression("/background-position:\s*(?:$side $vertical|$vertical $side)\b/i", $style);
            $this->assertMatchesRegularExpression('/background-size:\s*300px auto\b/i', $style);
            $this->assertStringNotContainsString($url, $text);
        }

        $responsiveCss = $xpath->evaluate('string(//head/style)');
        preg_match_all('/background-size:\s*([^;}]+)/i', $responsiveCss, $rules);
        $this->assertNotEmpty($rules[1]);
        foreach ($rules[1] as $size) {
            $this->assertStringContainsString('auto', $size, 'Changing the artwork width must preserve its proportions.');
        }

        $frame = $xpath->query('.//table[@class="mail-frame"]', $right->item(0));
        $this->assertSame(1, $frame->length);
        $this->assertSame('center', $frame->item(0)->getAttribute('align'));
        $this->assertMatchesRegularExpression('/max-width:\s*760px\b/i', $frame->item(0)->getAttribute('style'));
        $this->assertSame(0, $xpath->query('.//*[@background]', $frame->item(0))->length);

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

    public function test_classic_outlook_keeps_proportional_circle_images_beside_the_message(): void
    {
        $mail = (new MailNotification(['subject' => 'Test', 'body' => 'Eine kurze Nachricht.']))->toMail(new User);
        $html = (string) $mail->render();
        $this->assertStringNotContainsString('<v:', $html);

        // Parse the table markup that the conditional comments reveal to classic Outlook.
        $classic = preg_replace('/<!--\[if !mso\]><!-->.*?<!--<!\[endif\]-->/is', '', $html);
        $classic = preg_replace('/<!--\[if mso\]>(.*?)<!\[endif\]-->/is', '$1', $classic);
        $xpath = $this->parseMailHtml($classic);
        $images = $xpath->query('//img[contains(@src, "/background-circles-")]');
        $this->assertSame(2, $images->length);

        foreach (['left' => 'top', 'right' => 'bottom'] as $side => $vertical) {
            $url = 'https://www.regulierungs-check.de/site-images/mail/background-circles-'.$side.'.png';
            $image = $xpath->query("//img[@src='$url']")->item(0);
            $this->assertNotNull($image);
            $this->assertSame('65', $image->getAttribute('width'));
            $this->assertSame('104', $image->getAttribute('height'));
            $this->assertSame('', $image->getAttribute('alt'));
            $cell = $xpath->query('ancestor::td[1]', $image)->item(0);
            $this->assertSame($vertical, $cell->getAttribute('valign'));
        }

        $row = $xpath->query('ancestor::tr[1]', $images->item(0))->item(0);
        $cells = $xpath->query('./td', $row);
        $this->assertSame(3, $cells->length);
        $contentWidth = (int) $cells->item(1)->getAttribute('width');
        $artworkWidth = (int) $xpath->evaluate('sum(.//img[contains(@src, "/background-circles-")]/@width)', $row);
        $this->assertSame(630, $contentWidth);
        $this->assertLessThanOrEqual(760, $contentWidth + $artworkWidth, 'The artwork must fit within the mail width.');
        $this->assertStringContainsString('Eine kurze Nachricht.', $cells->item(1)->textContent);
        $this->assertSame(1, $xpath->query('.//img[contains(@src, "background-circles-right.png")]', $cells->item(2))->length);
    }

    private function parseMailHtml(string $html): DOMXPath
    {
        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        try {
            $document->loadHTML('<?xml encoding="UTF-8">'.$html);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        return new DOMXPath($document);
    }
}

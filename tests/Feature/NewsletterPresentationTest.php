<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Notifications\MailNotification;
use App\Services\NewsletterImage;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsletterPresentationTest extends TestCase
{
    public function test_one_two_to_one_version_per_focus_survives_changes_to_the_original(): void
    {
        $disk = Storage::fake('public');
        $source = 'uploads/news/hero.png';
        $disk->makeDirectory('uploads/news');
        $canvas = imagecreatetruecolor(241, 600);
        imagefilledrectangle($canvas, 0, 0, 240, 300, imagecolorallocate($canvas, 255, 20, 20));
        imagefilledrectangle($canvas, 0, 301, 240, 599, imagecolorallocate($canvas, 20, 20, 255));
        imagepng($canvas, $disk->path($source));
        $post = new Post(['title' => 'Ein Titel', 'cover_image' => $source]);
        $service = app(NewsletterImage::class);
        $top = $service->prepare($post, 50, 0);
        $bottom = $service->prepare($post, 50, 100);
        $this->assertSame([$top['width'], $top['height'], $top['alt']], [240, 120, 'Ein Titel']);
        $this->assertSame($top, $service->prepare($post, 50, 0));
        $this->assertNotSame($top['path'], $bottom['path']);
        $this->assertSame('jpg', pathinfo($top['path'], PATHINFO_EXTENSION));
        $this->assertLessThan(200000, $bottom['bytes']);
        $rgb = imagecolorat(imagecreatefromjpeg($disk->path($bottom['path'])), 50, 50);
        $this->assertGreaterThan(220, $rgb & 255);
        $this->assertLessThan(40, ($rgb >> 16) & 255);
        $disk->delete($source);
        $disk->assertExists([$top['path'], $bottom['path']]);
    }

    public function test_endpoint_requires_its_key_and_preserves_transparency_and_small_source_size(): void
    {
        $disk = Storage::fake('public');
        $disk->makeDirectory('uploads/news');
        $image = imagecreatetruecolor(60, 30);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 255, 255, 255, 127));
        imagepng($image, $disk->path('uploads/news/transparent.png'));
        config(['services.newsletter.api_key' => 'test-key', 'app.url' => 'https://public.test']);
        DB::statement('CREATE TABLE posts (id INTEGER PRIMARY KEY, type TEXT, title TEXT, cover_image TEXT, images TEXT)');
        DB::table('posts')->insert(['id' => 4, 'type' => 'news', 'title' => 'Beitrag', 'cover_image' => 'uploads/news/transparent.png']);
        $data = ['post_id' => 4, 'focus_x' => 50, 'focus_y' => 50];
        $url = '/api/admin/newsletter-image';
        $this->postJson($url, $data)->assertForbidden();
        $result = $this->withHeader('X-API-KEY', 'test-key')->postJson($url, $data)
            ->assertSuccessful()->assertJsonPath('image.width', 60)->assertJsonPath('image.height', 30);
        $this->assertStringEndsWith('.png', $result->json('image.path'));
        $this->assertStringStartsWith('https://public.test/storage/newsletter/bilder/', $result->json('image.url'));
    }

    public function test_newsletter_uses_the_same_article_and_distinct_personal_links_in_html_and_plain_text(): void
    {
        $article = 'https://news.test/news/42';
        $photo = 'https://news.test/storage/newsletter/bilder/version.jpg';
        $message = (new MailNotification([
            'subject' => 'November', 'header' => 'Hallo Alex,', 'body' => 'Ein gespeicherter Teaser.',
            'link' => $article, 'newsletter' => [
                'kind' => 'news', 'title' => 'Das ist <News>', 'preheader' => 'Vorschau & Neuigkeiten', 'image' => ['url' => $photo, 'alt' => 'Das Motiv'],
                'settings_url' => 'https://news.test/abo/auswahl', 'unsubscribe_url' => 'https://news.test/abo/beenden',
            ],
        ]))->toMail(new User);
        $html = (string) $message->render();
        $text = (string) app(Markdown::class)->renderText($message->markdown, $message->data());
        foreach ([$html, $text] as $part) {
            $this->assertStringContainsString('Hallo Alex,', $part);
            $this->assertStringContainsString('https://news.test/abo/auswahl', $part);
            $this->assertStringContainsString('https://news.test/abo/beenden', $part);
            $this->assertStringContainsString($article, $part);
        }
        $this->assertStringContainsString('Das ist &lt;News&gt;', $html);
        $this->assertStringContainsString('alt="Das Motiv"', $html);
        $this->assertStringContainsString('>Vorschau &amp; Neuigkeiten</div>', $html);
        $this->assertTrue(strpos($html, 'Vorschau &amp; Neuigkeiten') < strpos($html, '/site-images/logo-xs.png'));
        $this->assertStringNotContainsString('Vorschau & Neuigkeiten', $text);
        $this->assertStringNotContainsString($photo, $text);
        $this->assertSame(3, substr_count($html, 'href="'.$article.'"'));
        $this->assertTrue(strpos($html, $photo) < strpos($html, 'Ein gespeicherter Teaser.'));
        $this->assertTrue(strpos($html, 'Newsletter-Einstellungen ändern') < strpos($html, 'Viele Grüße'));
        $this->assertTrue(strpos($html, 'Newsletter abbestellen') > strpos($html, 'Viele Grüße'));
    }
}

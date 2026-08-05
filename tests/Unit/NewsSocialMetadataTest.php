<?php

namespace Tests\Unit;

use App\Livewire\Articles\News\NewsShow;
use App\Models\NewsCategory;
use App\Models\Post;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NewsSocialMetadataTest extends TestCase
{
    public function test_news_metadata_contains_the_article_preview_data(): void
    {
        $post = new Post([
            'title' => 'BGH stärkt Verbraucher',
            'slug' => 'bgh-staerkt-verbraucher',
            'type' => 'news',
            'excerpt' => '<strong>Versicherer</strong> müssen &amp; werden transparenter.',
            'published' => true,
            'published_at' => Carbon::parse('2026-08-01 10:30:00', 'Europe/Berlin'),
            'images' => [[
                'path' => 'uploads/news/bgh.jpg',
                'alt' => 'Richterhammer auf einem Tisch',
                'sort' => 0,
            ]],
        ]);
        $post->forceFill(['updated_at' => Carbon::parse('2026-08-02 08:15:00', 'Europe/Berlin')]);
        $post->setRelation('newsCategory', new NewsCategory(['name' => 'Urteil']));

        $meta = $this->metadataFor($post, false);
        $html = view('components.news-meta', ['meta' => $meta])->render();

        $this->assertSame('BGH stärkt Verbraucher', $meta['title']);
        $this->assertSame('Versicherer müssen & werden transparenter.', $meta['description']);
        $this->assertSame(route('news.show', $post), $meta['canonical']);
        $this->assertSame(asset('storage/uploads/news/bgh.jpg'), $meta['image']);
        $this->assertSame('Richterhammer auf einem Tisch', $meta['imageAlt']);
        $this->assertSame('Urteil', $meta['section']);
        $this->assertSame('index, follow, max-image-preview:large', $meta['robots']);
        $this->assertStringContainsString('<meta property="og:type" content="article">', $html);
        $this->assertStringContainsString('<meta property="og:title" content="BGH stärkt Verbraucher">', $html);
        $this->assertStringContainsString('property="og:image" content="'.e($meta['image']).'"', $html);
        $this->assertStringContainsString('property="article:published_time"', $html);
        $this->assertStringContainsString('property="article:modified_time"', $html);
        $this->assertStringContainsString('property="article:section" content="Urteil"', $html);
        $this->assertStringContainsString('<meta name="twitter:card" content="summary_large_image">', $html);
        $this->assertStringContainsString('<meta name="twitter:description"', $html);
        $this->assertStringContainsString('<link rel="canonical"', $html);
    }

    public function test_preview_and_content_fallbacks_are_safe_for_social_crawlers(): void
    {
        $post = new Post([
            'title' => 'News ohne Teaserbild',
            'slug' => 'news-ohne-teaserbild',
            'type' => 'news',
            'body' => '',
        ]);
        $post->setRelation('newsCategory', null);

        $meta = $this->metadataFor(
            $post,
            true,
            '<section><h2>PageBuilder Überschrift</h2><p>Der Kurztext kommt aus dem Inhalt.</p></section>'
        );

        $this->assertSame(
            'PageBuilder Überschrift Der Kurztext kommt aus dem Inhalt.',
            $meta['description']
        );
        $this->assertSame('noindex, nofollow, noarchive', $meta['robots']);
        $this->assertSame(asset('site-images/logo/preview-1200x630.jpg'), $meta['image']);
        $this->assertSame('News ohne Teaserbild', $meta['imageAlt']);
        $this->assertNull($meta['section']);
    }

    private function metadataFor(
        Post $post,
        bool $isAdminPreview,
        string $pagebuilderHtml = ''
    ): array {
        $component = new class extends NewsShow
        {
            public function metadata(Post $post, bool $isAdminPreview, string $pagebuilderHtml): array
            {
                return $this->newsMetadata($post, $isAdminPreview, $pagebuilderHtml);
            }
        };

        return $component->metadata($post, $isAdminPreview, $pagebuilderHtml);
    }
}

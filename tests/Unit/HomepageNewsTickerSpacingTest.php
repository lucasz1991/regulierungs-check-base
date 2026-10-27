<?php

namespace Tests\Unit;

use App\Models\NewsCategory;
use App\Models\Post;
use Tests\TestCase;

class HomepageNewsTickerSpacingTest extends TestCase
{
    public function test_ticker_renders_each_real_post_once_without_playback_or_scroll_controls(): void
    {
        $post = $this->newsPost();
        $html = view('livewire.banner.homepage-news-teaser-banner', [
            'newsEnabled' => true,
            'posts' => collect([$post]),
        ])->render();

        $this->assertSame(1, substr_count($html, 'class="news-card"'));
        $this->assertStringContainsString('data-news-ticker', $html);
        $this->assertStringNotContainsString('data-news-toggle', $html);
        $this->assertStringContainsString('data-carousel-track', $html);
        $this->assertStringNotContainsString('data-carousel-controls', $html);
        $this->assertStringNotContainsString('type="range"', $html);
        $this->assertStringNotContainsString('data-carousel-prev', $html);
        $this->assertStringNotContainsString('data-carousel-next', $html);
        $this->assertStringNotContainsString('news-band-heading', $html);
        $this->assertStringContainsString('aria-label="News &amp; Ratgeber"', $html);
        $this->assertStringContainsString('aria-label="Aktuelle News" tabindex="0"', $html);
        $this->assertStringContainsString(route('news.show', $post), $html);
        $this->assertStringContainsString('draggable="false"', $html);
        $this->assertStringNotContainsString('homepage-news-ticker__track', $html);
    }

    public function test_disabled_or_empty_news_do_not_create_a_ticker(): void
    {
        foreach ([[false, collect([$this->newsPost()])], [true, collect()]] as [$newsEnabled, $posts]) {
            $html = view('livewire.banner.homepage-news-teaser-banner', compact('newsEnabled', 'posts'))->render();
            $this->assertStringNotContainsString('data-news-ticker', $html);
        }
    }

    public function test_news_titles_teasers_and_categories_cannot_inject_active_markup(): void
    {
        $post = $this->newsPost();
        $post->title = '<img src=x onerror=alert(1)>Titel';
        $post->excerpt = '<script>alert(2)</script>Text';
        $post->setRelation('newsCategory', new NewsCategory(['name' => '<svg onload=alert(3)>']));
        $html = view('livewire.banner.homepage-news-teaser-banner', [
            'newsEnabled' => true,
            'posts' => collect([$post]),
        ])->render();

        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('<img', $html);
        $this->assertStringNotContainsString('<svg', $html);
        $this->assertStringContainsString('&lt;svg onload=alert(3)&gt;', $html);
    }

    private function newsPost(): Post
    {
        $post = new Post;
        $post->forceFill([
            'id' => 1,
            'title' => 'Aktuelle Nachricht',
            'slug' => 'aktuelle-nachricht',
            'type' => 'news',
            'excerpt' => 'Der vollständige Nachrichteninhalt bleibt erreichbar.',
            'updated_at' => now(),
        ]);
        $post->setRelation('newsCategory', null);
        $post->setRelation('pagebuilderProject', null);

        return $post;
    }
}

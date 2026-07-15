<?php

namespace Tests\Unit;

use App\Livewire\Articles\News\NewsShow;
use App\Models\PagebuilderProject;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class NewsFullPageBuilderLayoutTest extends TestCase
{
    public function test_legacy_full_page_news_layout_keeps_only_its_middle_content(): void
    {
        $builderHtml = <<<'HTML'
            <main class="rc-news-template" data-template="news-layout-01">
                <section class="rc-news-hero"><h1>Legacy Hero Marker</h1></section>
                <div class="rc-news-shell"><p>Editable Middle Marker</p></div>
                <section class="rc-news-related"><h2>Legacy Related Marker</h2></section>
            </main>
            <aside class="rc-news-related">Unrelated Custom Marker</aside>
            HTML;

        $html = $this->renderNewsShow($builderHtml, 999);

        $this->assertStringContainsString('Editable Middle Marker', $html);
        $this->assertStringNotContainsString('Legacy Hero Marker', $html);
        $this->assertStringNotContainsString('Legacy Related Marker', $html);
        $this->assertStringContainsString('Unrelated Custom Marker', $html);
        $this->assertStringContainsString('data-template-version="2"', $html);
        $this->assertStringContainsString('data-template-scope="content"', $html);
        $this->assertStringContainsString('news-pagebuilder-999', $html);
        $this->assertStringContainsString('Zurück zu News', $html);
        $this->assertSame(1, substr_count($html, 'Synthetic News'));
    }

    public function test_regular_builder_content_is_rendered_between_native_sections(): void
    {
        $html = $this->renderNewsShow(
            '<main data-template="news-layout-01" data-template-version="2" data-template-scope="content" style="padding: 20px"><p style="color: #142536">Regular Builder Marker</p></main>',
            998,
            collect([$this->relatedPost()])
        );

        $this->assertStringContainsString('Regular Builder Marker', $html);
        $this->assertStringContainsString('news-pagebuilder-998', $html);
        $this->assertStringContainsString('Zurück zu News', $html);
        $this->assertStringContainsString('Ähnliche Themen', $html);
        $this->assertStringContainsString('Related News Marker', $html);
        $this->assertStringContainsString('Alle anzeigen', $html);
        $this->assertStringContainsString('data-pagebuilder-project-css="998"', $html);
        $this->assertStringContainsString('.editor-css-marker { color: #084058; }', $html);
        $this->assertSame(1, substr_count($html, '.editor-css-marker { color: #084058; }'));
        $this->assertStringContainsString('data-pagebuilder-project-js="998"', $html);
        $this->assertSame(1, substr_count($html, 'window.newsEditorMarker = true;'));
        $this->assertStringNotContainsString('rc-news-template--content {', $html);
        $this->assertStringContainsString('container mx-auto px-3', $html);
        $this->assertMatchesRegularExpression(
            '/id="news-pagebuilder-998"[^>]*>\s*<div class="container mx-auto px-3">\s*<main/s',
            $html
        );
    }

    public function test_default_template_uses_its_own_container_without_a_nested_shell(): void
    {
        $html = $this->renderNewsShow(
            '<section data-template="news-layout-01" data-template-version="2" data-template-scope="content"><div class="container mx-auto px-3" data-news-container="true"><p>Owned Container Marker</p></div></section>',
            997
        );

        $this->assertStringContainsString('Owned Container Marker', $html);
        $this->assertMatchesRegularExpression(
            '/id="news-pagebuilder-997"[^>]*>\s*<section[^>]*>\s*<div class="container mx-auto px-3" data-news-container="true"/s',
            $html
        );
        $this->assertDoesNotMatchRegularExpression(
            '/id="news-pagebuilder-997"[^>]*>\s*<div class="container mx-auto px-3">\s*<section/s',
            $html
        );
    }

    public function test_fallback_uses_first_image_only_as_hero_and_secondary_images_in_content(): void
    {
        $post = $this->newsPost();
        $post->images = [
            ['path' => 'uploads/news/hero.jpg', 'alt' => 'Hero', 'sort' => 1],
            ['path' => 'uploads/news/secondary.jpg', 'alt' => 'Secondary', 'sort' => 2],
        ];
        $post->setRelation('pagebuilderProject', null);
        $post->setRelation('newsCategory', null);

        $html = view('livewire.articles.news.news-show', [
            'post' => $post,
            'relatedPosts' => collect(),
            'isAdminPreview' => false,
            'pagebuilderHtml' => '',
        ])->render();

        $this->assertSame(1, substr_count($html, '/storage/uploads/news/hero.jpg'));
        $this->assertSame(1, substr_count($html, '/storage/uploads/news/secondary.jpg'));
        $this->assertStringContainsString('Fallback Body Marker', $html);
    }

    public function test_news_list_and_cards_render_on_a_white_surface(): void
    {
        $post = $this->newsPost();
        $post->excerpt = 'List excerpt marker';
        $post->setRelation('pagebuilderProject', null);
        $post->setRelation('newsCategory', null);

        $posts = new LengthAwarePaginator(
            [$post],
            1,
            6,
            1,
            ['path' => route('news.index')]
        );

        $html = view('livewire.articles.news.news-list', [
            'posts' => $posts,
            'isAdminPreview' => false,
        ])->render();

        $this->assertStringContainsString('min-h-screen w-full bg-white', $html);
        $this->assertStringContainsString('container mx-auto px-3', $html);
        $this->assertStringNotContainsString('max-w-6xl', $html);
        $this->assertStringContainsString('border border-slate-200 bg-white', $html);
        $this->assertStringContainsString('Synthetic News', $html);
        $this->assertStringContainsString('List excerpt marker', $html);
    }

    private function renderNewsShow(
        string $builderHtml,
        int $projectId,
        $relatedPosts = null
    ): string {
        $post = $this->newsPost();

        $project = new PagebuilderProject([
            'cleaned_html' => $builderHtml,
            'css' => '.editor-css-marker { color: #084058; }',
            'js' => 'window.newsEditorMarker = true;',
        ]);
        $project->id = $projectId;

        $post->setRelation('pagebuilderProject', $project);
        $post->setRelation('newsCategory', null);

        return view('livewire.articles.news.news-show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts ?? collect(),
            'isAdminPreview' => false,
            'pagebuilderHtml' => $this->contentOnlyPagebuilderHtml($builderHtml),
        ])->render();
    }

    private function contentOnlyPagebuilderHtml(string $html): string
    {
        $component = new class extends NewsShow
        {
            public function normalizePagebuilderHtml(string $html): string
            {
                return $this->contentOnlyPagebuilderHtml($html);
            }
        };

        return $component->normalizePagebuilderHtml($html);
    }

    private function newsPost(): Post
    {
        return new Post([
            'title' => 'Synthetic News',
            'slug' => 'synthetic-news',
            'type' => 'news',
            'body' => '<p>Fallback Body Marker</p>',
            'layout' => 'image_top',
        ]);
    }

    private function relatedPost(): Post
    {
        $post = new Post([
            'title' => 'Related News Marker',
            'slug' => 'related-news-marker',
            'type' => 'news',
        ]);
        $post->setRelation('newsCategory', null);

        return $post;
    }
}

<?php

namespace Tests\Unit;

use App\Models\PagebuilderProject;
use App\Models\Post;
use Tests\TestCase;

class NewsFullPageBuilderLayoutTest extends TestCase
{
    public function test_full_page_news_layout_replaces_the_native_news_shell(): void
    {
        $html = $this->renderNewsShow(
            '<main class="rc-news-template" data-template="news-layout-01">Full Page Marker</main>',
            999
        );

        $this->assertStringContainsString('Full Page Marker', $html);
        $this->assertStringContainsString('news-pagebuilder-999', $html);
        $this->assertStringNotContainsString('Zurück zu News', $html);
        $this->assertStringNotContainsString('overflow-hidden rounded-2xl bg-white/95 shadow-xl', $html);
    }

    public function test_regular_builder_content_keeps_the_native_news_shell(): void
    {
        $html = $this->renderNewsShow('<p>Regular Builder Marker</p>', 998);

        $this->assertStringContainsString('Regular Builder Marker', $html);
        $this->assertStringContainsString('news-pagebuilder-998', $html);
        $this->assertStringContainsString('Zurück zu News', $html);
        $this->assertStringContainsString('overflow-hidden rounded-2xl bg-white/95 shadow-xl', $html);
    }

    private function renderNewsShow(string $builderHtml, int $projectId): string
    {
        $post = new Post([
            'title' => 'Synthetic News',
            'type' => 'news',
        ]);

        $project = new PagebuilderProject([
            'cleaned_html' => $builderHtml,
            'css' => '.marker {}',
            'js' => '',
        ]);
        $project->id = $projectId;

        $post->setRelation('pagebuilderProject', $project);

        return view('livewire.articles.news.news-show', [
            'post' => $post,
            'relatedPosts' => collect(),
            'isAdminPreview' => false,
        ])->render();
    }
}

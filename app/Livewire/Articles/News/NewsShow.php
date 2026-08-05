<?php

namespace App\Livewire\Articles\News;

use App\Models\Post;
use App\Models\Setting;
use App\Support\NewsPreviewAccess;
use App\Support\PublicNewsCache;
use Illuminate\Support\Str;
use Livewire\Component;

class NewsShow extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        $publicNewsEnabled = Setting::enabled('webcontent', 'news_enabled', false);
        $isAdminPreview = app(NewsPreviewAccess::class)->isActive(request());

        $this->ensurePostIsAccessible($post, $publicNewsEnabled, $isAdminPreview);

        $this->post = $this->loadPostRelations($post, $isAdminPreview);
    }

    public function render()
    {
        $publicNewsEnabled = Setting::enabled('webcontent', 'news_enabled', false);
        $isAdminPreview = app(NewsPreviewAccess::class)->isActive(request());

        $this->ensurePostIsAccessible($this->post, $publicNewsEnabled, $isAdminPreview);

        if ($isAdminPreview) {
            $this->post->loadMissing(['newsCategory', 'pagebuilderProject']);

            $relatedPosts = Post::where('type', 'news')
                ->where('id', '!=', $this->post->id)
                ->with('newsCategory')
                ->latest('updated_at')
                ->limit(3)
                ->get();
        } else {
            $newsCache = app(PublicNewsCache::class);
            $generation = $newsCache->generation();

            $this->post = $this->loadPostRelations($this->post, false, $generation);
            $this->ensurePostIsAccessible($this->post, $publicNewsEnabled, false);

            $relatedPosts = $newsCache->remember(
                'related',
                [
                    'post_id' => $this->post->getKey(),
                    'limit' => 3,
                ],
                fn () => Post::where('type', 'news')
                    ->where('id', '!=', $this->post->id)
                    ->with('newsCategory')
                    ->published()
                    ->latest('published_at')
                    ->limit(3)
                    ->get(),
                $generation
            );
        }

        $pagebuilderHtml = $this->contentOnlyPagebuilderHtml(
            $this->post->pagebuilderProject?->cleaned_html
        );

        return view('livewire.articles.news.news-show', [
            'relatedPosts' => $relatedPosts,
            'isAdminPreview' => $isAdminPreview,
            'pagebuilderHtml' => $pagebuilderHtml,
        ])->layout('layouts.app', [
            'newsMeta' => $this->newsMetadata($this->post, $isAdminPreview, $pagebuilderHtml),
        ]);
    }

    /**
     * Link previews are assembled by social platforms from server-rendered
     * metadata. Keeping this data on the News page means existing and future
     * PageBuilder projects receive the same preview without a data migration.
     *
     * @return array<string, string|null>
     */
    protected function newsMetadata(Post $post, bool $isAdminPreview, string $pagebuilderHtml): array
    {
        $title = $this->plainText($post->title) ?: config('app.name', 'Regulierungs-Check');
        $description = $this->newsDescription($post, $pagebuilderHtml);
        $image = $post->firstNewsImage();
        $imageUrl = (string) ($image['url'] ?? '');

        if ($imageUrl === '') {
            $imageUrl = asset('site-images/logo/preview-1200x630.jpg');
        } elseif (! Str::startsWith($imageUrl, ['http://', 'https://'])) {
            $imageUrl = url('/'.ltrim($imageUrl, '/'));
        }

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => route('news.show', $post),
            'robots' => $isAdminPreview
                ? 'noindex, nofollow, noarchive'
                : 'index, follow, max-image-preview:large',
            'image' => $imageUrl,
            'imageAlt' => $this->plainText((string) ($image['alt'] ?? '')) ?: $title,
            'publishedTime' => $post->published_at?->toIso8601String(),
            'modifiedTime' => $post->updated_at?->toIso8601String(),
            'section' => $this->plainText((string) ($post->newsCategory?->name ?? '')) ?: null,
        ];
    }

    protected function newsDescription(Post $post, string $pagebuilderHtml): string
    {
        foreach ([$post->excerpt, $post->body, $pagebuilderHtml] as $source) {
            $description = $this->plainText((string) $source);

            if ($description !== '') {
                return Str::limit($description, 200, '…');
            }
        }

        return 'Aktuelle Nachricht und Einordnung von Regulierungs-Check.';
    }

    protected function plainText(string $value): string
    {
        $withoutTags = preg_replace('/<[^>]*>/u', ' ', $value) ?? $value;

        return Str::squish(html_entity_decode($withoutTags, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    protected function ensurePostIsAccessible(
        Post $post,
        bool $publicNewsEnabled,
        bool $isAdminPreview
    ): void {
        abort_unless(
            $post->type === 'news'
                && (
                    $isAdminPreview
                    || (
                        $publicNewsEnabled
                        && $post->published
                        && $post->published_at?->lte(now())
                    )
                ),
            404
        );
    }

    private function loadPostRelations(
        Post $post,
        bool $isAdminPreview,
        ?string $generation = null
    ): Post {
        if ($isAdminPreview) {
            return $post->load(['newsCategory', 'pagebuilderProject']);
        }

        $newsCache = app(PublicNewsCache::class);

        return $newsCache->remember(
            'detail',
            ['post_id' => $post->getKey()],
            fn () => Post::query()
                ->with(['newsCategory', 'pagebuilderProject'])
                ->findOrFail($post->getKey()),
            $generation ?? $newsCache->generation()
        );
    }

    /**
     * Older News templates contained their own hero and related-news section.
     * Those parts are now rendered by the application, so only the editable
     * article fragment may remain in the PageBuilder output.
     */
    protected function contentOnlyPagebuilderHtml(?string $html): string
    {
        $html = trim((string) $html);

        if (
            $html === ''
            || ! str_contains($html, 'rc-news-template')
            || (! str_contains($html, 'rc-news-hero') && ! str_contains($html, 'rc-news-related'))
        ) {
            return $html;
        }

        $document = new \DOMDocument('1.0', 'UTF-8');
        $previousUseInternalErrors = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadHTML(
                '<?xml encoding="utf-8" ?><!DOCTYPE html><html><body><div id="rc-news-builder-root">'
                    .$html
                    .'</div></body></html>',
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );

            if (! $loaded) {
                return $html;
            }

            $xpath = new \DOMXPath($document);
            $templateRoots = $xpath->query(
                '//*[contains(concat(" ", normalize-space(@class), " "), " rc-news-template ")]'
            );

            if ($templateRoots !== false) {
                foreach (iterator_to_array($templateRoots) as $templateRoot) {
                    $legacySections = $xpath->query(
                        './/*[contains(concat(" ", normalize-space(@class), " "), " rc-news-hero ")'
                        .' or contains(concat(" ", normalize-space(@class), " "), " rc-news-related ")]',
                        $templateRoot
                    );

                    if ($legacySections !== false) {
                        foreach (iterator_to_array($legacySections) as $section) {
                            $section->parentNode?->removeChild($section);
                        }
                    }

                    $templateRoot->setAttribute(
                        'class',
                        trim($templateRoot->getAttribute('class').' rc-news-template--content')
                    );
                    $templateRoot->setAttribute('data-template-version', '2');
                    $templateRoot->setAttribute('data-template-scope', 'content');
                }
            }

            $wrapper = $document->getElementById('rc-news-builder-root');

            if (! $wrapper) {
                return $html;
            }

            $content = '';

            foreach ($wrapper->childNodes as $child) {
                $content .= $document->saveHTML($child);
            }

            return trim($content);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);
        }
    }
}

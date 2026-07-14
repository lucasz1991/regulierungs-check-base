<?php

namespace App\Livewire\Articles\News;

use App\Models\Post;
use App\Models\Setting;
use App\Support\NewsPreviewAccess;
use Livewire\Component;

class NewsShow extends Component
{
    public Post $post;
    public bool $isAdminPreview = false;

    public function mount(Post $post): void
    {
        $publicNewsEnabled = Setting::enabled('webcontent', 'news_enabled', false);
        $this->isAdminPreview = app(NewsPreviewAccess::class)->isActive(request());

        abort_unless(
            $post->type === 'news'
                && (
                    $this->isAdminPreview
                    || ($publicNewsEnabled && $post->published && $post->published_at !== null)
                ),
            404
        );

        $this->post = $post->load(['newsCategory', 'pagebuilderProject']);
    }

    public function render()
    {
        $relatedPostsQuery = Post::where('type', 'news')
            ->where('id', '!=', $this->post->id)
            ->with('newsCategory');

        if ($this->isAdminPreview) {
            $relatedPostsQuery->latest('updated_at');
        } else {
            $relatedPostsQuery->published()->latest('published_at');
        }

        $relatedPosts = $relatedPostsQuery->limit(3)->get();

        return view('livewire.articles.news.news-show', [
            'relatedPosts' => $relatedPosts,
            'isAdminPreview' => $this->isAdminPreview,
            'pagebuilderHtml' => $this->contentOnlyPagebuilderHtml(
                $this->post->pagebuilderProject?->cleaned_html
            ),
        ])->layout('layouts.app');
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
                    . $html
                    . '</div></body></html>',
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
                        . ' or contains(concat(" ", normalize-space(@class), " "), " rc-news-related ")]',
                        $templateRoot
                    );

                    if ($legacySections !== false) {
                        foreach (iterator_to_array($legacySections) as $section) {
                            $section->parentNode?->removeChild($section);
                        }
                    }

                    $templateRoot->setAttribute(
                        'class',
                        trim($templateRoot->getAttribute('class') . ' rc-news-template--content')
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

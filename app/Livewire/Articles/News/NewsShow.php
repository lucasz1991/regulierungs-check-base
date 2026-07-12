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
        ])->layout('layouts.app');
    }
}
